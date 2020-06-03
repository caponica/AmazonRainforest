<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestStockEstimation;
use CaponicaAmazonRainforest\Response\StockEstimationResponse;

/**
 * Wrapper for the parameters used when making a StockEstimation request.
 *
 * @package CaponicaAmazonRainforest\Request
 */
class StockEstimationRequest extends CommonRequest
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Request\\StockEstimationRequest';

    protected $amazon_domain;
    protected $asin;

    public function __construct($site, $asin)
    {
        $this->amazon_domain = $site;
        $this->asin = $asin;
    }

    public static function getReflectionArray() {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => StockEstimationResponse::CLASS_NAME,
            'entityClass'   => RainforestStockEstimation::CLASS_NAME,
            'debug'         => 'StockEstimation',
        ];
    }

    public function getQueryKeys() {
        $queryKeys = [
            'amazon_domain',
            'asin'
        ];
        return $queryKeys;
    }

    public function getQueryType() {
        return RainforestClient::REQUEST_TYPE_STOCK_ESTIMATION;
    }

    /**
     * A unique key for this ProductRequest
     *
     * @return string
     */
    public function getKeyLong() {
        return $this->amazon_domain . '~' . $this->asin;
    }
}