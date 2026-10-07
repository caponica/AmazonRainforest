<?php

namespace CaponicaAmazonRainforest\Client;

use CaponicaAmazonRainforest\Entity\RainforestCollection;
use CaponicaAmazonRainforest\Entity\RainforestCollectionRequest;
use CaponicaAmazonRainforest\Entity\RainforestCollectionResultSet;
use CaponicaAmazonRainforest\Exception\RainforestCollectionBusyException;
use CaponicaAmazonRainforest\Exception\RainforestCollectionException;
use CaponicaAmazonRainforest\Exception\RainforestCollectionNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Client for the Rainforest Collections API. Calls are made one at a time, as the docs require.
 * "not found" is HTTP 404 only, and "busy" a failure message containing "running", until real responses are pinned.
 */
class RainforestCollectionClient
{
    const BASE_URL = 'https://api.rainforestapi.com';
    const MAX_REQUESTS_PER_ADD = 1000;
    const MAX_REQUESTS_PER_COLLECTION = 15000;
    const MAX_429_RETRIES = 3;

    private string $apiKey;
    private ?LoggerInterface $logger;
    private ClientInterface $httpClient;
    private \Closure $sleeper;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config, ?LoggerInterface $logger = null, ?ClientInterface $httpClient = null, ?\Closure $sleeper = null)
    {
        if (empty($config['api_key'])) {
            throw new \InvalidArgumentException('Missing Rainforest API key');
        }
        $this->apiKey = $config['api_key'];
        $this->logger = $logger;
        $this->httpClient = $httpClient ?? new Client(['timeout' => 120, 'connect_timeout' => 15]);
        $this->sleeper = $sleeper ?? function (int $seconds): void { sleep($seconds); };
    }

    /**
     * @param array<string, mixed> $params
     */
    public function createCollection(array $params): RainforestCollection
    {
        $data = $this->callApi('POST', '/collections', ['json' => $params]);
        if (empty($data['collection']['id'])) {
            // Rainforest may already have created it: keep the raw response (it never contains the api_key) for the log
            throw new RainforestCollectionException('Unexpected create response, no collection.id: ' . json_encode($data));
        }

        return new RainforestCollection($data['collection']);
    }

    public function fetchCollection(string $collectionId): RainforestCollection
    {
        $data = $this->callApi('GET', "/collections/$collectionId");
        if (empty($data['collection']) || !is_array($data['collection'])) {
            throw new RainforestCollectionException("Unexpected fetch response for Collection $collectionId, no collection: " . json_encode($data));
        }

        return new RainforestCollection($data['collection']);
    }

    public function deleteCollection(string $collectionId): void
    {
        $this->callApi('DELETE', "/collections/$collectionId");
    }

    /**
     * @param list<array<string, mixed>> $requests
     */
    public function addRequests(string $collectionId, array $requests): void
    {
        if (self::MAX_REQUESTS_PER_ADD < count($requests)) {
            throw new \InvalidArgumentException('At most ' . self::MAX_REQUESTS_PER_ADD . ' requests can be added per call, got ' . count($requests));
        }

        $this->callApi('PUT', "/collections/$collectionId", ['json' => ['requests' => $requests]]);
    }

    /**
     * @return list<RainforestCollectionRequest>
     */
    public function fetchRequestsPage(string $collectionId, int $page): array
    {
        return $this->buildRequests($this->callApi('GET', "/collections/$collectionId/requests/$page"));
    }

    /**
     * Takes the page count from page 1 itself, and refuses an inconsistent answer: a page count of 0 while requests
     * exist would make add re-add every schedule and remove unlink schedules whose requests stay live
     * @return list<RainforestCollectionRequest>
     */
    public function fetchAllRequests(string $collectionId): array
    {
        $firstPage = $this->callApi('GET', "/collections/$collectionId/requests/1");
        $collection = new RainforestCollection(['id' => $collectionId] + $firstPage);
        $pageCount = $collection->getRequestsPageCount();
        $total = $collection->getRequestsTotalCount();
        $requests = $this->buildRequests($firstPage);
        $firstPageCount = count($requests);
        $consistent = !(0 === $pageCount && (0 < $total || 0 < $firstPageCount))
            && !(self::MAX_REQUESTS_PER_ADD <= $firstPageCount && 1 >= $pageCount)
            && !($pageCount < (int) ceil($total / self::MAX_REQUESTS_PER_ADD));
        if (!$consistent) {
            throw new RainforestCollectionException("Collection $collectionId request listing is inconsistent: total $total, page count $pageCount, $firstPageCount on page 1");
        }

        for ($page = 2; $page <= $pageCount; $page++) {
            array_push($requests, ...$this->fetchRequestsPage($collectionId, $page));
        }

        return $requests;
    }

    /**
     * @param array<string, mixed> $data
     * @return list<RainforestCollectionRequest>
     */
    private function buildRequests(array $data): array
    {
        return array_map(fn(array $request) => new RainforestCollectionRequest($request), $data['requests'] ?? []);
    }

    /**
     * @param list<string> $requestIds
     */
    public function deleteRequests(string $collectionId, array $requestIds): void
    {
        if (empty($requestIds)) {
            return;
        }
        $this->callApi('DELETE', "/collections/$collectionId/requests", ['json' => array_values($requestIds)]);
    }

    /**
     * @return list<RainforestCollectionResultSet>
     */
    public function fetchResultSets(string $collectionId): array
    {
        $data = $this->callApi('GET', "/collections/$collectionId/results");

        return array_map(fn(array $set) => new RainforestCollectionResultSet($set), $data['results'] ?? []);
    }

    /**
     * @return list<string>
     */
    public function fetchResultSetPageLinks(string $collectionId, int $resultSetId): array
    {
        $data = $this->callApi('GET', "/collections/$collectionId/results/$resultSetId/jsonlines");

        return array_values($data['result']['download_links']['pages'] ?? []);
    }

    public function fetchResultPageBody(string $url): string
    {
        try {
            $response = $this->httpClient->request('GET', $url, ['http_errors' => false]);
        } catch (GuzzleException $e) {
            throw new RainforestCollectionException('Result page download failed: ' . get_class($e));
        }
        $httpCode = $response->getStatusCode();
        if (200 !== $httpCode) {
            throw new RainforestCollectionException("Result page download failed with HTTP $httpCode", $httpCode);
        }

        return (string) $response->getBody();
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function callApi(string $method, string $path, array $options = []): array
    {
        $options['query'] = ['api_key' => $this->apiKey];
        $options['http_errors'] = false;

        $response = $this->sendWithRetry($method, $path, $options);
        $httpCode = $response->getStatusCode();
        $data = json_decode((string) $response->getBody(), true);
        $message = is_array($data) ? ($data['request_info']['message'] ?? null) : null;

        if (404 === $httpCode) {
            throw new RainforestCollectionNotFoundException($message ?? "Not found: $method $path", $httpCode);
        }
        if (200 > $httpCode || 300 <= $httpCode || !is_array($data) || empty($data['request_info']['success'])) {
            $message ??= "Rainforest Collections call failed: $method $path (HTTP $httpCode)";
            if (str_contains(strtolower($message), 'running')) {
                throw new RainforestCollectionBusyException($message, $httpCode);
            }
            throw new RainforestCollectionException($message, $httpCode);
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function sendWithRetry(string $method, string $path, array $options): ResponseInterface
    {
        $attempt = 0;
        while (true) {
            try {
                $response = $this->httpClient->request($method, self::BASE_URL . $path, $options);
            } catch (GuzzleException $e) {
                // not chained: Guzzle's transport messages include the full URI, and the query string holds the api_key
                throw new RainforestCollectionException("Rainforest Collections request failed: $method $path (" . get_class($e) . ')');
            }
            if (429 !== $response->getStatusCode() || self::MAX_429_RETRIES <= $attempt) {
                return $response;
            }
            $attempt++;
            $this->logger?->warning("Rainforest Collections returned HTTP 429 for $method $path, retry $attempt");
            ($this->sleeper)(2 ** $attempt);
        }
    }
}
