<?php

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Exception\RainforestCollectionException;

/**
 * The only place that knows a Collection result line's shape: the wrapper observed on a real result page,
 * {success, id, request, total_time_taken, result}. result holds request_parameters, request_metadata and
 * product but no request_info, so one is built from the wrapper's success flag. A request whose product is not
 * found also reports success, with result.message in place of product.
 */
class RainforestCollectionResultItem
{
    private const REQUIRED_RESPONSE_KEYS = ['request_info', 'request_metadata', 'request_parameters', 'product'];
    // total_time_taken is also in result.request_metadata, so the wrapper's copy is ignored
    private const KNOWN_WRAPPER_KEYS = ['id', 'success', 'request', 'total_time_taken', 'result'];
    // rf:sp treats a response without a product as one failed request (e.g. a delisted ASIN), so a page does the same;
    // used when result.message does not say why
    private const NO_PRODUCT_MESSAGE = 'Response reported success but has no product';

    private array $line;
    private bool $success;
    private ?array $responseData;
    private ?string $customId;
    private ?string $failureMessage;

    private function __construct(array $line, bool $success, ?array $responseData, ?string $customId, ?string $failureMessage)
    {
        $this->line = $line;
        $this->success = $success;
        $this->responseData = $responseData;
        $this->customId = $customId;
        $this->failureMessage = $failureMessage;
    }

    /**
     * @return list<string>
     */
    public static function splitJsonLines(string $body): array
    {
        $lines = preg_split('/\r\n|\n|\r/', $body);

        return array_values(array_filter($lines, fn(string $line) => '' !== trim($line)));
    }

    public static function fromJsonLine(string $line): self
    {
        try {
            $data = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RainforestCollectionException("Result line is not valid JSON: {$e->getMessage()}", null, $e);
        }
        if (!is_array($data)) {
            throw new RainforestCollectionException('Result line is not a JSON object');
        }

        if (array_key_exists('success', $data) && array_key_exists('result', $data)) {
            return self::fromWrapper($data);
        }

        throw new RainforestCollectionException('Unrecognised result line structure, top-level keys: ' . implode(',', array_keys($data)));
    }

    private static function fromWrapper(array $data): self
    {
        $unknownKeys = array_filter(
            array_diff_key($data, array_flip(self::KNOWN_WRAPPER_KEYS)),
            fn($value) => !(is_null($value) || '' === $value || [] === $value),
        );
        if (!empty($unknownKeys)) {
            throw new RainforestCollectionException('Unknown result line wrapper key(s): ' . implode(',', array_keys($unknownKeys)));
        }
        $request = is_array($data['request'] ?? null) ? $data['request'] : [];
        $customId = isset($request['custom_id']) ? (string) $request['custom_id'] : null;
        $result = $data['result'];

        if (empty($data['success'])) {
            $message = is_array($result) ? ($result['request_info']['message'] ?? null) : null;
            return new self($data, false, null, $customId, $message);
        }
        if (!is_array($result)) {
            throw new RainforestCollectionException('Successful result line has no result object');
        }
        if (empty($result['product'])) {
            $message = !empty($result['message']) && is_string($result['message']) ? $result['message'] : self::NO_PRODUCT_MESSAGE;
            return new self($data, false, null, $customId, $message);
        }
        $result['request_parameters'] = array_merge($request, $result['request_parameters'] ?? []);
        if (!isset($result['request_info'])) {
            $result['request_info'] = ['success' => (bool) $data['success']];
        }

        return new self($data, true, self::validateResponse($result), $customId, null);
    }

    private static function validateResponse(array $response): array
    {
        foreach (self::REQUIRED_RESPONSE_KEYS as $key) {
            if (empty($response[$key]) || !is_array($response[$key])) {
                throw new RainforestCollectionException("Successful result line is missing $key");
            }
        }
        if (empty($response['product']['asin'])) {
            throw new RainforestCollectionException('Successful result line is missing product.asin');
        }
        if (empty($response['request_parameters']['amazon_domain'])) {
            throw new RainforestCollectionException('Successful result line is missing request_parameters.amazon_domain');
        }

        return $response;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function getFailureMessage(): ?string
    {
        return $this->failureMessage;
    }

    public function getResponseData(): array
    {
        if (is_null($this->responseData)) {
            throw new RainforestCollectionException('A failed result line has no response data');
        }

        return $this->responseData;
    }

    public function getCreditsUsed(): ?int
    {
        $credits = $this->responseData['request_info']['credits_used_this_request'] ?? null;

        return is_null($credits) ? null : (int) $credits;
    }

    public function getDataAsLastResort(): array
    {
        return $this->line;
    }
}
