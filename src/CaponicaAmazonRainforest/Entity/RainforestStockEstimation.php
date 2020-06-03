<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Response\StockEstimationResponse;

/**
 * Converts a StockEstimationResponse into an object representing the response. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestStockEstimation extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestStockEstimation';

    /**
     * @param CommonResponse $rfResponse    A StockEstimationResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var StockEstimationResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);

        $this->setAsin($rfResponse->getAsin());
        $this->setMarketplace($rfResponse->getMarketplaceSuffix());
        $this->setStockLevel($rfResponse->getStockLevel());
        $this->setMessage($rfResponse->getMessage());
        $this->setPriceCurrency($rfResponse->getPriceCurrency());
        $this->setPriceAmount($rfResponse->getPriceAmount());
        $this->setMinQuantity($rfResponse->getMinQuantity());
        $this->setIsInStock($rfResponse->getIsInStock());
        $this->setIsPrime($rfResponse->getIsPrime());
    }

    /**
     * @var string
     */
    protected $asin;

    /**
     * @var string
     */
    protected $marketplace;

    /**
     * @var integer
     */
    protected $stockLevel;

    /**
     * @var string
     */
    protected $priceCurrency;

    /**
     * @var string
     */
    protected $priceAmount;

    /**
     * @var integer
     */
    protected $minQuantity;

    /**
     * @var boolean
     */
    protected $isInStock = false;

    /**
     * @var boolean
     */
    protected $isPrime = false;

    /**
     * @var string
     */
    protected $message;


    /**
     * Set asin
     *
     * @param string $asin
     *
     * @return RainforestStockEstimation
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;

        return $this;
    }

    /**
     * Get asin
     *
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * Set marketplace
     *
     * @param string $marketplace
     *
     * @return RainforestStockEstimation
     */
    public function setMarketplace($marketplace)
    {
        $this->marketplace = $marketplace;

        return $this;
    }

    /**
     * Get marketplace
     *
     * @return string
     */
    public function getMarketplace()
    {
        return $this->marketplace;
    }

    /**
     * Set stockLevel
     *
     * @param integer $stockLevel
     *
     * @return RainforestStockEstimation
     */
    public function setStockLevel($stockLevel)
    {
        $this->stockLevel = $stockLevel;

        return $this;
    }

    /**
     * Get stockLevel
     *
     * @return integer
     */
    public function getStockLevel()
    {
        return $this->stockLevel;
    }

    /**
     * Set priceCurrency
     *
     * @param string $priceCurrency
     *
     * @return RainforestStockEstimation
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    /**
     * Get priceCurrency
     *
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * Set priceAmount
     *
     * @param string $priceAmount
     *
     * @return RainforestStockEstimation
     */
    public function setPriceAmount($priceAmount)
    {
        $this->priceAmount = $priceAmount;

        return $this;
    }

    /**
     * Get priceAmount
     *
     * @return string
     */
    public function getPriceAmount()
    {
        return $this->priceAmount;
    }

    /**
     * Set minQuantity
     *
     * @param integer $minQuantity
     *
     * @return RainforestStockEstimation
     */
    public function setMinQuantity($minQuantity)
    {
        $this->minQuantity = $minQuantity;

        return $this;
    }

    /**
     * Get minQuantity
     *
     * @return integer
     */
    public function getMinQuantity()
    {
        return $this->minQuantity;
    }

    /**
     * Set isInStock
     *
     * @param boolean $isInStock
     *
     * @return RainforestStockEstimation
     */
    public function setIsInStock($isInStock)
    {
        $this->isInStock = $isInStock;

        return $this;
    }

    /**
     * Get isInStock
     *
     * @return boolean
     */
    public function getIsInStock()
    {
        return $this->isInStock;
    }

    /**
     * Set isPrime
     *
     * @param boolean $isPrime
     *
     * @return RainforestStockEstimation
     */
    public function setIsPrime($isPrime)
    {
        $this->isPrime = $isPrime;

        return $this;
    }

    /**
     * Get isPrime
     *
     * @return boolean
     */
    public function getIsPrime()
    {
        return $this->isPrime;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return RainforestStockEstimation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
