<?php

namespace CaponicaAmazonRainforest\Response;

/**
 * Wrapper for the raw response received back from a request to the Rainforest StockEstimation API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class StockEstimationResponse extends CommonResponse
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Response\\StockEstimationResponse';

    const MAIN_KEY_STOCK_ESTIMATION = 'stock_estimation';

    private $stock_estimation = null;

    public function __construct($rfData)
    {
        parent::__construct($rfData);

        // main product data:
        $this->stock_estimation         = &$this->data[self::MAIN_KEY_STOCK_ESTIMATION];
    }

    public static function getMainKeys() {
        $keys = parent::getMainKeys();
        $keys[] = self::MAIN_KEY_STOCK_ESTIMATION;
        return $keys;
    }

    public function getStockEstimationField($key, $valueIfMissing=null) {
        if (empty($this->stock_estimation[$key])) {
            return $valueIfMissing;
        }
        return $this->stock_estimation[$key];
    }

    public function getStockLevel() {
        return $this->getStockEstimationField('stock_level');
    }
    public function getMessage() {
        return $this->getStockEstimationField('message');
    }
    public function getPriceCurrency() {
        $price = $this->getStockEstimationField('price');
        if (empty($price) || empty($price['currency'])) {
            return null;
        }

        return $price['currency'];
    }
    public function getPriceAmount() {
        $price = $this->getStockEstimationField('price');
        if (empty($price) || empty($price['value'])) {
            return null;
        }

        return $price['value'];
    }
    public function getMinQuantity() {
        return $this->getStockEstimationField('minQuantity');
    }
    public function getIsInStock() {
        return $this->getStockEstimationField('isInStock') ? true : false;
    }
    public function getIsPrime() {
        return $this->getStockEstimationField('isPrime') ? true : false;
    }
    public function getAsin() {
        return $this->getStockEstimationField('asin');
    }
}