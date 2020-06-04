<?php /** @noinspection PhpUndefinedClassInspection */

namespace CaponicaAmazonRainforest\Client;

use CaponicaAmazonRainforest\Entity\RainforestBestSellers;
use CaponicaAmazonRainforest\Entity\RainforestEntityCommon;
use CaponicaAmazonRainforest\Entity\RainforestProduct;
use CaponicaAmazonRainforest\Entity\RainforestStockEstimation;
use CaponicaAmazonRainforest\Request\BestSellersRequest;
use CaponicaAmazonRainforest\Request\CategoryRequest;
use CaponicaAmazonRainforest\Request\CommonRequest;
use CaponicaAmazonRainforest\Request\OfferRequest;
use CaponicaAmazonRainforest\Request\ProductRequest;
use CaponicaAmazonRainforest\Request\ReviewRequest;
use CaponicaAmazonRainforest\Request\SearchRequest;
use CaponicaAmazonRainforest\Request\StockEstimationRequest;
use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Service\LoggerService;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
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

    const REQUEST_TYPE_BEST_SELLER      = 'bestsellers';
    const REQUEST_TYPE_CATEGORY         = 'category';
    const REQUEST_TYPE_OFFERS           = 'offers';
    const REQUEST_TYPE_PRODUCT          = 'product';
    const REQUEST_TYPE_REVIEWS          = 'reviews';
    const REQUEST_TYPE_SEARCH           = 'search';
    const REQUEST_TYPE_STOCK_ESTIMATION = 'stock_estimation';

    /** @var LoggerInterface $logger */
    protected $logger;
    /** @var string $apiKey*/
    private $apiKey;
    /** @var bool $debugFilePath */
    private $debugFilePath = null;
    /** @var bool $debugInput       Set true to read responses from a file, instead of calling the API */
    private $debugInput = false;
    /** @var bool $debugOutput      Set true to save response to a file */
    private $debugOutput = false;

    /**
     * @param array $config             Must include "api_key" with value. Other options are debug_file_path, debug_input, debug_output.
     * @param LoggerInterface $logger
     */
    public function __construct($config, LoggerInterface $logger = null) {
        $this->logger           = $logger;

        $this->apiKey           = $config['api_key'];
        if (empty($this->apiKey)) {
            throw new \InvalidArgumentException('Missing Rainforest API key');
        }

        if (!empty($config['debug_file_path'])) {
            $this->debugFilePath = $config['debug_file_path'];
            if (!empty($config['debug_input'])) {
                $this->debugInput = true;
            }
            if (!empty($config['debug_output'])) {
                $this->debugOutput = true;
            }
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
     * Checks and de-duplicates an array of CommonRequests. Returns an array of Requests indexed by getKey()
     *
     * @param CommonRequest[] $requests    Array keys are ignored from the input array (and are not needed)
     * @return array
     */
    public function prepareRequestArray($requests) {
        if (!is_array($requests)) {
            $requests = [ $requests ];
        }

        $cleanArray = [];
        foreach ($requests as $request) {
            $cleanArray[$request->getKey()] = $request;
        }

        return $cleanArray;
    }

    /**
     * @param BestSellersRequest|BestSellersRequest[] $requests             The BestSellersRequest(s) to process and retrieve.
     * @param RainforestBestSellers|RainforestBestSellers[] $rfBestSellers  RainforestBestSellers object (or Array indexed by getKey()).
     *                                                                      If set then they will be updated from the response,
     *                                                                      instead of creating new ones.
     * @return RainforestBestSellers[]
     * @throws \Exception
     */
    public function retrieveBestSellers($requests, $rfBestSellers=null) {
        /** @var RainforestBestSellers[] $bestSellers */
        $bestSellers = $this->retrieveObjects(BestSellersRequest::getReflectionArray(), $requests, $rfBestSellers);
        return $bestSellers;
    }
    /**
     * @param CategoryRequest|CategoryRequest[] $requests           The CategoryRequest(s) to process and retrieve.
     * @param RainforestCategory|RainforestCategory[] $rfCats       RainforestCategory object (or Array indexed by getKey()).
     *                                                              If set then they will be updated from the response,
     *                                                              instead of creating new ones.
     * @return RainforestCategory[]
     * @throws \Exception
     */
    public function retrieveCategories($requests, $rfCats=null) {
        /** @var RainforestCategory[] $cats*/
        $cats = $this->retrieveObjects(CategoryRequest::getReflectionArray(), $requests, $rfCats);
        return $cats;
    }
    /**
     * @param OfferRequest|OfferRequest[] $requests                 The OfferRequest(s) to process and retrieve.
     * @param RainforestOffer|RainforestOffer[] $rfOffers           RainforestOffer object (or Array indexed by getKey()).
     *                                                              If set then they will be updated from the response,
     *                                                              instead of creating new ones.
     * @return RainforestOffer[]
     * @throws \Exception
     */
    public function retrieveOffers($requests, $rfOffers=null) {
        /** @var RainforestOffer[] $offers */
        $offers = $this->retrieveObjects(OfferRequest::getReflectionArray(), $requests, $rfOffers);
        return $offers;
    }
    /**
     * @param ProductRequest|ProductRequest[] $requests             The ProductRequest(s) to process and retrieve.
     * @param RainforestProduct|RainforestProduct[] $rfProducts     RainforestProduct object (or Array indexed by getKey()).
     *                                                              If set then they will be updated from the response,
     *                                                              instead of creating new ones.
     * @return RainforestProduct[]
     * @throws \Exception
     */
    public function retrieveProducts($requests, $rfProducts=null) {
        /** @var RainforestProduct[] $products */
        $products = $this->retrieveObjects(ProductRequest::getReflectionArray(), $requests, $rfProducts);
        return $products;
    }
    /**
     * @param ReviewRequest|ReviewRequest[] $requests               The ReviewRequest(s) to process and retrieve.
     * @param RainforestReview|RainforestReview[] $rfReviews        RainforestReview object (or Array indexed by getKey()).
     *                                                              If set then they will be updated from the response,
     *                                                              instead of creating new ones.
     * @return RainforestReview[]
     * @throws \Exception
     */
    public function retrieveReviews($requests, $rfReviews=null) {
        /** @var RainforestReview[] $reviews */
        $reviews = $this->retrieveObjects(ReviewRequest::getReflectionArray(), $requests, $rfReviews);
        return $reviews;
    }
    /**
     * @param SearchRequest|SearchRequest[] $requests               The SearchRequest(s) to process and retrieve.
     * @param RainforestSearch|RainforestSearch[] $rfSearches       RainforestSearch object (or Array indexed by getKey()).
     *                                                              If set then they will be updated from the response,
     *                                                              instead of creating new ones.
     * @return RainforestSearch[]
     * @throws \Exception
     */
    public function retrieveSearches($requests, $rfSearches=null) {
        /** @var RainforestSearch[] $searches */
        $searches = $this->retrieveObjects(SearchRequest::getReflectionArray(), $requests, $rfSearches);
        return $searches;
    }
    /**
     * @param StockEstimationRequest|StockEstimationRequest[] $requests         The StockEstimationRequest(s) to process and retrieve.
     * @param RainforestStockEstimation|RainforestStockEstimation[] $rfObjects  RainforestStockEstimation object (or Array indexed by getKey()).
     *                                                                          If set then they will be updated from the response,
     *                                                                          instead of creating new ones.
     * @return RainforestStockEstimation[]
     * @throws \Exception
     */
    public function retrieveStockEstimations($requests, $rfObjects=null) {
        /** @var RainforestStockEstimation[] $objects */
        $objects = $this->retrieveObjects(StockEstimationRequest::getReflectionArray(), $requests, $rfObjects);
        return $objects;
    }
    /**
     * @param array $reflectionArray
     * @param CommonRequest|CommonRequest[] $requests   The CommonRequest(s) to process and retrieve.
     * @param $rfObjects                                Rainforest objects (or Array indexed by getKey()). If set then they
     *                                                  will be updated from the response, instead of creating new ones.
     * @return RainforestEntityCommon[]
     * @throws \Exception
     */
    private function retrieveObjects($reflectionArray, $requests, $rfObjects=null) {
        $debugName = 'retrieve' . $reflectionArray['debug'] . 's';

        $convertedSingleParamIntoArray = false;
        if (!is_array($requests) && $requests instanceof $reflectionArray['requestClass']) {
            $requests = [ $requests->getKey() => $requests ];
            $convertedSingleParamIntoArray = true;
        }

        if (empty($rfObjects)) {
            $rfObjects = [];
        } elseif (!is_array($rfObjects) && $rfObjects instanceof $reflectionArray['entityClass']) {
            if ($convertedSingleParamIntoArray) {
                $rfObjects = [ array_key_first($requests) => $rfObjects ];
            } else {
                throw new \InvalidArgumentException("If you provide multiple Requests to $debugName() then you cannot provide a single {$reflectionArray['entityClass']} in the second parameter");
            }
        }

        if ($this->debugInput) {
            $rfResponses = $this->fetchResponsesFromTestFile($requests, $reflectionArray['responseClass']);
        } else {
            $rfResponses = $this->fetchResponsesFromApi($requests, $reflectionArray['responseClass']);
        }

        foreach ($requests as $request) {
            if (empty($rfResponses[$request->getKey()])) {
                continue;
            }

            if (empty($rfObjects[$request->getKey()])) {
                $rfObjects[$request->getKey()] = new $reflectionArray['entityClass']();
            }
            $rfObjects[$request->getKey()]->updateFromRainforestResponse($rfResponses[$request->getKey()]);
        }

        return $rfObjects;
    }

    /**
     * @param CommonRequest[] $requests
     * @param string $responseClass
     * @return CommonResponse[]
     * @throws \Exception
     */
    private function fetchResponsesFromApi($requests, $responseClass) {
        $client = new Client();
        $promiseRequests = [];
        $rfResponses = [];
        $debugName = substr($responseClass, strrpos($responseClass, '\\')+1);

        foreach ($requests as $request) {
            $queryString = http_build_query($request->buildQueryArray($this->apiKey));
            $this->logMessage("Requesting $debugName data from API for {$request->getKey()}", LoggerService::DEBUG);
            $this->logMessage("Query string is $queryString", LoggerService::DEBUG);
            $promiseRequests[$request->getKey()] = $client->getAsync(sprintf('https://api.rainforestapi.com/request?%s', $queryString));
        }

        /** @var CommonResponse[] $responses */
        $responses = Promise\settle($promiseRequests)->wait();
        foreach ($responses as $key => $response) {
            try {
                $data = $this->validateResponseAndReturnData($response, $responseClass);
                $rfResponses[$key] = new $responseClass($data);
            } catch (\Exception $e) {
                $this->logMessage("Could not extract $debugName data from response {$key}. Message: " . $e->getMessage(), LoggerService::ERROR);
            }
        }

        return $rfResponses;
    }
    /**
     * @param CommonRequest[] $requests
     * @param string $responseClass
     * @return CommonResponse[]
     * @throws \Exception
     */
    private function fetchResponsesFromTestFile($requests, $responseClass) {
        $rfResponses = [];
        $responses = [];
        $debugName = substr($responseClass, strrpos($responseClass, '\\')+1);

        foreach ($requests as $key => $request) {
            $queryString = http_build_query($request->buildQueryArray($this->apiKey));
            $this->logMessage("Loading from test file, but would normally call https://api.rainforestapi.com/request?$queryString", LoggerService::DEBUG);
            $responses[$key] = $this->debugLoadResponseFromFile();
        }

        foreach ($responses as $key => $response) {
            try {
                $data = json_decode($response, true);
                $rfResponses[$key] = new $responseClass($data);
            } catch (\Exception $e) {
                $this->logMessage("Could not extract $debugName data from response {$key}. Message: " . $e->getMessage(), LoggerService::ERROR);
            }
        }

        return $rfResponses;
    }

    /**
     * @param ResponseInterface $response
     * @param string $responseClass             The namespaced class of the expected response
     * @return array
     * @throws \Exception
     */
    private function validateResponseAndReturnData($response, $responseClass) {
        if (is_array($response) && array_key_exists('state', $response)) {
            if (Promise\PromiseInterface::FULFILLED === $response['state']) {
                $response = $response['value'];
            } elseif (array_key_exists('reason', $response) && $response['reason'] instanceof \Exception) {
                throw $response['reason'];
            }
        }

        if ($this->debugOutput) {
            $this->debugSaveResponseToFile($response);
        }
        $responseCode = $response->getStatusCode();
        if ($responseCode !== 200) {
            throw new \Exception("Rainforest request failed. HTTP response was $responseCode. See https://rainforestapi.com/docs/response-codes for more details.");
        }

        $dataArray = json_decode($response->getBody(), true);
        //$this->logMessage(print_r($dataArray, true), LoggerService::DEBUG);  // ### ONLY FOR DEBUGGING ###

        foreach (forward_static_call([$responseClass, 'getMainKeys']) as $key) {
            if (empty($dataArray[$key])) {
                $this->logMessage("Dump of data object returned from Rainforest:", LoggerService::DEBUG);
                $this->logMessage(print_r($dataArray, true), LoggerService::DEBUG);
                throw new \Exception("Rainforest response appears incomplete. It is missing field $key.");
            }
        }

        if (empty($dataArray[CommonResponse::MAIN_KEY_REQUEST_INFO]['success'])) {
            $this->logMessage("Dump of data object returned from Rainforest:", LoggerService::DEBUG);
            $this->logMessage(print_r($dataArray, true), LoggerService::DEBUG);
            throw new \Exception("Rainforest response does not contain 'request_info.success=true'.");
        }

        return $dataArray;
    }

    /**
     * Logs with a given level.
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
            // echo $message; // Users can uncomment this line instead of creating an EchoLogger if they need to.
        }
    }

    protected function getDebugFileName() {
        return $this->debugFilePath;
    }
    protected function debugSaveResponseToFile(ResponseInterface $response) {
        $fileHandle = @fopen($this->getDebugFileName(), 'w+');
        fwrite($fileHandle, $response->getBody());
        fclose($fileHandle);
        $this->logMessage('Saved response to file ' . $this->getDebugFileName(), LoggerService::DEBUG);
    }
    protected function debugLoadResponseFromFile() {
        $responseText = file_get_contents($this->getDebugFileName());
        $this->logMessage('Loaded response from file ' . $this->getDebugFileName(), LoggerService::DEBUG);
        return $responseText;
    }
}