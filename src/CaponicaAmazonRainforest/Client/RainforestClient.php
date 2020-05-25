<?php

namespace CaponicaAmazonRainforest\Client;

use CaponicaAmazonRainforest\Response\ProductResponse;
use CaponicaAmazonRainforest\Service\LoggerService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Client used to interact with the underlying API
 *
 * @package CaponicaAmazonRainforest\Client
 */
class RainforestClient
{
    const AMAZON_SITE_AUSTRALIA     = 'amazon.com.au';
    const AMAZON_SITE_BRAZIL        = 'amazon.com.br';
    const AMAZON_SITE_CANADA        = 'amazon.ca';
    const AMAZON_SITE_FRANCE        = 'amazon.fr';
    const AMAZON_SITE_GERMANY       = 'amazon.de';
    const AMAZON_SITE_INDIA         = 'amazon.in';
    const AMAZON_SITE_ITALY         = 'amazon.it';
    const AMAZON_SITE_JAPAN         = 'amazon.co.jp';
    const AMAZON_SITE_MEXICO        = 'amazon.com.mx';
    const AMAZON_SITE_NETHERLANDS   = 'amazon.nl';
    const AMAZON_SITE_SPAIN         = 'amazon.es';
    const AMAZON_SITE_UAE           = 'amazon.ae';
    const AMAZON_SITE_UK            = 'amazon.co.uk';
    const AMAZON_SITE_USA           = 'amazon.com';

    /** @var LoggerInterface $logger */
    protected $logger;
    /** @var string $apiKey*/
    private $apiKey;

    /**
     * @param array $config
     * @param LoggerInterface $logger
     */
    public function __construct($config, LoggerInterface $logger = null) {
        $this->logger           = $logger;

        $this->apiKey           = $config['api_key'];
        if (empty($this->apiKey)) {
            throw new \InvalidArgumentException('Missing Rainforest API key');
        }
    }

    public function getValidAmazonSitesArray() {
        return [
            self::AMAZON_SITE_AUSTRALIA,
            self::AMAZON_SITE_BRAZIL,
            self::AMAZON_SITE_CANADA,
            self::AMAZON_SITE_FRANCE,
            self::AMAZON_SITE_GERMANY,
            self::AMAZON_SITE_INDIA,
            self::AMAZON_SITE_ITALY,
            self::AMAZON_SITE_JAPAN,
            self::AMAZON_SITE_MEXICO,
            self::AMAZON_SITE_NETHERLANDS,
            self::AMAZON_SITE_SPAIN,
            self::AMAZON_SITE_UAE,
            self::AMAZON_SITE_UK,
            self::AMAZON_SITE_USA,
        ];
    }
    public function isValidAmazonSite($siteString) {
        return in_array($siteString, $this->getValidAmazonSitesArray());
    }
    public function getValidAmazonSiteSuffixArray() {
        return [
            self::AMAZON_SITE_AUSTRALIA     => 'com.au',
            self::AMAZON_SITE_BRAZIL        => 'com.br',
            self::AMAZON_SITE_CANADA        => 'ca',
            self::AMAZON_SITE_FRANCE        => 'fr',
            self::AMAZON_SITE_GERMANY       => 'de',
            self::AMAZON_SITE_INDIA         => 'in',
            self::AMAZON_SITE_ITALY         => 'it',
            self::AMAZON_SITE_JAPAN         => 'co.jp',
            self::AMAZON_SITE_MEXICO        => 'com.mx',
            self::AMAZON_SITE_NETHERLANDS   => 'nl',
            self::AMAZON_SITE_SPAIN         => 'es',
            self::AMAZON_SITE_UAE           => 'ae',
            self::AMAZON_SITE_UK            => 'co.uk',
            self::AMAZON_SITE_USA           => 'com',
        ];
    }
    public function convertAmazonSiteToSuffix($amazonSite) {
        $suffixes = $this->getValidAmazonSiteSuffixArray();
        if (empty($suffixes[$amazonSite])) {
            throw new \InvalidArgumentException('Unknown Amazon Site: ' . $amazonSite);
        }
        return $suffixes[$amazonSite];
    }

    /**
     * @param string $asin              The ASIN to retrieve
     * @param string $amazonDomain      One of the AMAZON_SITE_XYZ constants
     * @return ProductResponse|null
     * @throws \Exception
     */
    public function retrieveProductDataForAsinOnSite($asin, $amazonDomain) {
        if (!$this->isValidAmazonSite($amazonDomain)) {
            $this->logMessage("Invalid Amazon site provided: $amazonDomain", LoggerService::ERROR);
            return null;
        }

        // retrieve using API
        $rfProductResponse = $this->fetchProductDataForAsinOnSite($asin, $amazonDomain);
        if (empty($rfProductResponse)) {
            return null;
        }

        return $rfProductResponse;
    }

    /**
     * @param string $asin
     * @param string $amazonDomain
     * @return ProductResponse
     * @throws \Exception
     */
    private function fetchProductDataForAsinOnSite($asin, $amazonDomain) {
        $queryString = http_build_query([
            'type'          => 'product',
            'api_key'       => $this->apiKey,
            'amazon_domain' => $amazonDomain,
            'asin'          => $asin,
        ]);

        $client = new Client();
        try {
            $response = $client->request('GET', sprintf('https://api.rainforestapi.com/request?%s', $queryString));
            $data = $this->validateResponseAndReturnData($response);
            $rfProduct = new ProductResponse($data);
        } catch (GuzzleException $e) {
            $this->logMessage('ERROR: Guzzle Exception trying to retrieve data from Rainforest API. Message: ' . $e->getMessage(), LoggerService::ERROR);
            throw new \Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        } catch (\Exception $e) {
            $this->logMessage('ERROR: General Exception trying to retrieve data from Rainforest API. Message: ' . $e->getMessage(), LoggerService::ERROR);
            throw $e;
        }

        return $rfProduct;
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws \Exception
     */
    private function validateResponseAndReturnData($response) {
        $responseCode = $response->getStatusCode();
        if ($responseCode !== 200) {
            throw new \Exception("Rainforest request failed. HTTP response was $responseCode. See https://rainforestapi.com/docs/response-codes for more details.");
        }

        $dataArray = json_decode($response->getBody(), true);

        foreach (ProductResponse::getMainKeys() as $key) {
            if (empty($dataArray[$key])) {
                $this->logMessage("Dump of data object returned from Rainforest:", LoggerService::DEBUG);
                $this->logMessage(print_r($dataArray, true), LoggerService::DEBUG);
                throw new \Exception("Rainforest response appears incomplete. It is missing field $key.");
            }
        }

        if (empty($dataArray[ProductResponse::MAIN_KEY_REQUEST_INFO]['success'])) {
            $this->logMessage("Dump of data object returned from Rainforest:", LoggerService::DEBUG);
            $this->logMessage(print_r($dataArray, true), LoggerService::DEBUG);
            throw new \Exception("Rainforest response does not contain 'request_info.success=true'.");
        }

        return $dataArray;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    protected function logMessage($message, $level, $context = [])
    {
        if ($this->logger) {
            // Use the internal logger for logging.
            $this->logger->log($level, $message, $context);
        } else {
            echo $message;
        }
    }
}