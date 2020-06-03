<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Response\ProductResponse;

/**
 * Converts a ProductResponse into an object representing a product. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestProduct extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestProduct';

    /**
     * @param CommonResponse $rfResponse    A ProductResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var ProductResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);
        $this->setAsin($rfResponse->getAsin());
        $this->setMarketplace($rfResponse->getMarketplaceSuffix());
        $this->setTitle($rfResponse->getTitle());
        $this->setFirstAvailable($rfResponse->getFirstAvailableDate());
        $this->setMainImageLink($rfResponse->getMainImageLink());
        $this->setImageCount($rfResponse->getImageCount());
        $this->setVideoCount($rfResponse->getVideoCount());
        $this->setSalesRank($rfResponse->getSalesRank());
        $this->setSalesRankTlc($rfResponse->getSalesRankTlc());
        $this->setHasCoupon($rfResponse->getHasCoupon());
        $this->setRating50($rfResponse->getRating50());
        $this->setRatingsTotal($rfResponse->getRatingsTotal());
        $this->setReviewsTotal($rfResponse->getReviewsTotal());
        $this->setHasAPlusContent($rfResponse->getHasAPlusContent());
        $this->setIsAPlusThirdParty($rfResponse->getIsAPlusThirdParty());
        $this->setAPlusCompany($rfResponse->getAPlusCompany());
        $this->setModelNumber($rfResponse->getModelNumber());
        $this->setRecommendedAge($rfResponse->getRecommendedAge());
        $this->setLanguage($rfResponse->getLanguage());
        $this->setBbIsPrime($rfResponse->getBbIsPrime());
        $this->setBbIsConditionNew($rfResponse->getBbIsConditionNew());
        $this->setBbAvailabilityType($rfResponse->getBbAvailabilityType());
        $this->setBbAvailabilityStock($rfResponse->getBbAvailabilityStock());
        $this->setBbDispatchDays($rfResponse->getBbDispatchDays());
        $this->setBbAvailabilityRaw($rfResponse->getBbAvailabilityRaw());
        $this->setBbFulType($rfResponse->getBbFulType());
        $this->setBbFulSellerName($rfResponse->getBbFulSellerName());
        $this->setBbPriceCurrency($rfResponse->getBbPriceCurrency());
        $this->setBbPriceAmount($rfResponse->getBbPriceAmount());

        // ProductResponse methods that can throw Exceptions:
        try {
            $this->setWeightPounds($rfResponse->getWeightPounds());
        } catch (\Exception $e) {
            $this->setWeightPounds(null);
        }
        try {
            $this->setWeightShippingPounds($rfResponse->getWeightShippingPounds());
        } catch (\Exception $e) {
            $this->setWeightShippingPounds(null);
        }
        try {
            $this->setDimensionsInches($rfResponse->getDimensionsInchesString());
        } catch (\Exception $e) {
            $this->setDimensionsInches(null);
        }
        try {
            $this->setVolumeCuFt($rfResponse->getVolumeCuFt());
        } catch (\Exception $e) {
            $this->setVolumeCuFt(null);
        }
    }


    /**
     * @var string
     */
    protected $asin;

    /**
     * Domain suffix, e.g. "co.uk", "com" or "de"
     *
     * @var string
     */
    protected $marketplace;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \DateTime
     */
    protected $firstAvailable;

    /**
     * @var string
     */
    protected $mainImageLink;

    /**
     * @var integer
     */
    protected $imageCount = 0;

    /**
     * @var integer
     */
    protected $videoCount = 0;

    /**
     * @var integer
     */
    protected $salesRank;

    /**
     * @var string
     */
    protected $salesRankTlc;

    /**
     * @var boolean
     */
    protected $hasCoupon = false;

    /**
     * Integer rating out of 50 (e.g. 4.8 stars => 48)
     *
     * @var integer
     */
    protected $rating50;

    /**
     * @var integer
     */
    protected $ratingsTotal;

    /**
     * @var integer
     */
    protected $reviewsTotal;

    /**
     * @var boolean
     */
    protected $hasAPlusContent = false;

    /**
     * @var boolean
     */
    protected $isAPlusThirdParty = false;

    /**
     * @var string
     */
    protected $aPlusCompany;

    /**
     * @var string
     */
    protected $weightPounds;

    /**
     * @var string
     */
    protected $weightShippingPounds;

    /**
     * String representation of dimensions, largest dimension first. E.g. 7x5.2x3.1
     *
     * @var string
     */
    protected $dimensionsInches;

    /**
     * Decimal volume in cubic feet, calculated from dimensions
     *
     * @var string
     */
    protected $volumeCuFt;

    /**
     * @var string
     */
    protected $modelNumber;

    /**
     * @var string
     */
    protected $recommendedAge;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var boolean
     */
    protected $bbIsPrime = false;

    /**
     * @var boolean
     */
    protected $bbIsConditionNew = true;

    /**
     * @var string
     */
    protected $bbAvailabilityType;

    /**
     * @var integer
     */
    protected $bbAvailabilityStock;

    /**
     * @var integer
     */
    protected $bbDispatchDays;

    /**
     * @var string
     */
    protected $bbAvailabilityRaw;

    /**
     * @var string
     */
    protected $bbFulType;

    /**
     * @var string
     */
    protected $bbFulSellerName;

    /**
     * @var string
     */
    protected $bbPriceCurrency;

    /**
     * @var string
     */
    protected $bbPriceAmount;


    /**
     * Set asin
     *
     * @param string $asin
     *
     * @return RainforestProduct
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
     * @return RainforestProduct
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
     * Set title
     *
     * @param string $title
     *
     * @return RainforestProduct
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set firstAvailable
     *
     * @param \DateTime $firstAvailable
     *
     * @return RainforestProduct
     */
    public function setFirstAvailable($firstAvailable)
    {
        $this->firstAvailable = $firstAvailable;

        return $this;
    }

    /**
     * Get firstAvailable
     *
     * @return \DateTime
     */
    public function getFirstAvailable()
    {
        return $this->firstAvailable;
    }

    /**
     * Set mainImageLink
     *
     * @param string $mainImageLink
     *
     * @return RainforestProduct
     */
    public function setMainImageLink($mainImageLink)
    {
        $this->mainImageLink = $mainImageLink;

        return $this;
    }

    /**
     * Get mainImageLink
     *
     * @return string
     */
    public function getMainImageLink()
    {
        return $this->mainImageLink;
    }

    /**
     * Set imageCount
     *
     * @param integer $imageCount
     *
     * @return RainforestProduct
     */
    public function setImageCount($imageCount)
    {
        $this->imageCount = $imageCount;

        return $this;
    }

    /**
     * Get imageCount
     *
     * @return integer
     */
    public function getImageCount()
    {
        return $this->imageCount;
    }

    /**
     * Set videoCount
     *
     * @param integer $videoCount
     *
     * @return RainforestProduct
     */
    public function setVideoCount($videoCount)
    {
        $this->videoCount = $videoCount;

        return $this;
    }

    /**
     * Get videoCount
     *
     * @return integer
     */
    public function getVideoCount()
    {
        return $this->videoCount;
    }

    /**
     * Set salesRank
     *
     * @param integer $salesRank
     *
     * @return RainforestProduct
     */
    public function setSalesRank($salesRank)
    {
        $this->salesRank = $salesRank;

        return $this;
    }

    /**
     * Get salesRank
     *
     * @return integer
     */
    public function getSalesRank()
    {
        return $this->salesRank;
    }

    /**
     * Set salesRankTlc
     *
     * @param string $salesRankTlc
     *
     * @return RainforestProduct
     */
    public function setSalesRankTlc($salesRankTlc)
    {
        $this->salesRankTlc = $salesRankTlc;

        return $this;
    }

    /**
     * Get salesRankTlc
     *
     * @return string
     */
    public function getSalesRankTlc()
    {
        return $this->salesRankTlc;
    }

    /**
     * Set hasCoupon
     *
     * @param boolean $hasCoupon
     *
     * @return RainforestProduct
     */
    public function setHasCoupon($hasCoupon)
    {
        $this->hasCoupon = $hasCoupon;

        return $this;
    }

    /**
     * Get hasCoupon
     *
     * @return boolean
     */
    public function getHasCoupon()
    {
        return $this->hasCoupon;
    }

    /**
     * Set rating50
     *
     * @param integer $rating50
     *
     * @return RainforestProduct
     */
    public function setRating50($rating50)
    {
        $this->rating50 = $rating50;

        return $this;
    }

    /**
     * Get rating50
     *
     * @return integer
     */
    public function getRating50()
    {
        return $this->rating50;
    }

    /**
     * Set ratingsTotal
     *
     * @param integer $ratingsTotal
     *
     * @return RainforestProduct
     */
    public function setRatingsTotal($ratingsTotal)
    {
        $this->ratingsTotal = $ratingsTotal;

        return $this;
    }

    /**
     * Get ratingsTotal
     *
     * @return integer
     */
    public function getRatingsTotal()
    {
        return $this->ratingsTotal;
    }

    /**
     * Set reviewsTotal
     *
     * @param integer $reviewsTotal
     *
     * @return RainforestProduct
     */
    public function setReviewsTotal($reviewsTotal)
    {
        $this->reviewsTotal = $reviewsTotal;

        return $this;
    }

    /**
     * Get reviewsTotal
     *
     * @return integer
     */
    public function getReviewsTotal()
    {
        return $this->reviewsTotal;
    }

    /**
     * Set hasAPlusContent
     *
     * @param boolean $hasAPlusContent
     *
     * @return RainforestProduct
     */
    public function setHasAPlusContent($hasAPlusContent)
    {
        $this->hasAPlusContent = $hasAPlusContent;

        return $this;
    }

    /**
     * Get hasAPlusContent
     *
     * @return boolean
     */
    public function getHasAPlusContent()
    {
        return $this->hasAPlusContent;
    }

    /**
     * Set isAPlusThirdParty
     *
     * @param boolean $isAPlusThirdParty
     *
     * @return RainforestProduct
     */
    public function setIsAPlusThirdParty($isAPlusThirdParty)
    {
        $this->isAPlusThirdParty = $isAPlusThirdParty;

        return $this;
    }

    /**
     * Get isAPlusThirdParty
     *
     * @return boolean
     */
    public function getIsAPlusThirdParty()
    {
        return $this->isAPlusThirdParty;
    }

    /**
     * Set aPlusCompany
     *
     * @param string $aPlusCompany
     *
     * @return RainforestProduct
     */
    public function setAPlusCompany($aPlusCompany)
    {
        $this->aPlusCompany = $aPlusCompany;

        return $this;
    }

    /**
     * Get aPlusCompany
     *
     * @return string
     */
    public function getAPlusCompany()
    {
        return $this->aPlusCompany;
    }

    /**
     * Set weightPounds
     *
     * @param string $weightPounds
     *
     * @return RainforestProduct
     */
    public function setWeightPounds($weightPounds)
    {
        $this->weightPounds = $weightPounds;

        return $this;
    }

    /**
     * Get weightPounds
     *
     * @return string
     */
    public function getWeightPounds()
    {
        return $this->weightPounds;
    }

    /**
     * Set weightShippingPounds
     *
     * @param string $weightShippingPounds
     *
     * @return RainforestProduct
     */
    public function setWeightShippingPounds($weightShippingPounds)
    {
        $this->weightShippingPounds = $weightShippingPounds;

        return $this;
    }

    /**
     * Get weightShippingPounds
     *
     * @return string
     */
    public function getWeightShippingPounds()
    {
        return $this->weightShippingPounds;
    }

    /**
     * Set dimensionsInches
     *
     * @param string $dimensionsInches
     *
     * @return RainforestProduct
     */
    public function setDimensionsInches($dimensionsInches)
    {
        $this->dimensionsInches = $dimensionsInches;

        return $this;
    }

    /**
     * Get dimensionsInches
     *
     * @return string
     */
    public function getDimensionsInches()
    {
        return $this->dimensionsInches;
    }

    /**
     * Set volumeCuFt
     *
     * @param string $volumeCuFt
     *
     * @return RainforestProduct
     */
    public function setVolumeCuFt($volumeCuFt)
    {
        $this->volumeCuFt = $volumeCuFt;

        return $this;
    }

    /**
     * Get volumeCuFt
     *
     * @return string
     */
    public function getVolumeCuFt()
    {
        return $this->volumeCuFt;
    }

    /**
     * Set modelNumber
     *
     * @param string $modelNumber
     *
     * @return RainforestProduct
     */
    public function setModelNumber($modelNumber)
    {
        $this->modelNumber = $modelNumber;

        return $this;
    }

    /**
     * Get modelNumber
     *
     * @return string
     */
    public function getModelNumber()
    {
        return $this->modelNumber;
    }

    /**
     * Set recommendedAge
     *
     * @param string $recommendedAge
     *
     * @return RainforestProduct
     */
    public function setRecommendedAge($recommendedAge)
    {
        $this->recommendedAge = $recommendedAge;

        return $this;
    }

    /**
     * Get recommendedAge
     *
     * @return string
     */
    public function getRecommendedAge()
    {
        return $this->recommendedAge;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return RainforestProduct
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set bbIsPrime
     *
     * @param boolean $bbIsPrime
     *
     * @return RainforestProduct
     */
    public function setBbIsPrime($bbIsPrime)
    {
        $this->bbIsPrime = $bbIsPrime;

        return $this;
    }

    /**
     * Get bbIsPrime
     *
     * @return boolean
     */
    public function getBbIsPrime()
    {
        return $this->bbIsPrime;
    }

    /**
     * Set bbIsConditionNew
     *
     * @param boolean $bbIsConditionNew
     *
     * @return RainforestProduct
     */
    public function setBbIsConditionNew($bbIsConditionNew)
    {
        $this->bbIsConditionNew = $bbIsConditionNew;

        return $this;
    }

    /**
     * Get bbIsConditionNew
     *
     * @return boolean
     */
    public function getBbIsConditionNew()
    {
        return $this->bbIsConditionNew;
    }

    /**
     * Set bbAvailabilityType
     *
     * @param string $bbAvailabilityType
     *
     * @return RainforestProduct
     */
    public function setBbAvailabilityType($bbAvailabilityType)
    {
        $this->bbAvailabilityType = $bbAvailabilityType;

        return $this;
    }

    /**
     * Get bbAvailabilityType
     *
     * @return string
     */
    public function getBbAvailabilityType()
    {
        return $this->bbAvailabilityType;
    }

    /**
     * Set bbAvailabilityStock
     *
     * @param integer $bbAvailabilityStock
     *
     * @return RainforestProduct
     */
    public function setBbAvailabilityStock($bbAvailabilityStock)
    {
        $this->bbAvailabilityStock = $bbAvailabilityStock;

        return $this;
    }

    /**
     * Get bbAvailabilityStock
     *
     * @return integer
     */
    public function getBbAvailabilityStock()
    {
        return $this->bbAvailabilityStock;
    }

    /**
     * Set bbDispatchDays
     *
     * @param integer $bbDispatchDays
     *
     * @return RainforestProduct
     */
    public function setBbDispatchDays($bbDispatchDays)
    {
        $this->bbDispatchDays = $bbDispatchDays;

        return $this;
    }

    /**
     * Get bbDispatchDays
     *
     * @return integer
     */
    public function getBbDispatchDays()
    {
        return $this->bbDispatchDays;
    }

    /**
     * Set bbAvailabilityRaw
     *
     * @param string $bbAvailabilityRaw
     *
     * @return RainforestProduct
     */
    public function setBbAvailabilityRaw($bbAvailabilityRaw)
    {
        $this->bbAvailabilityRaw = $bbAvailabilityRaw;

        return $this;
    }

    /**
     * Get bbAvailabilityRaw
     *
     * @return string
     */
    public function getBbAvailabilityRaw()
    {
        return $this->bbAvailabilityRaw;
    }

    /**
     * Set bbFulType
     *
     * @param string $bbFulType
     *
     * @return RainforestProduct
     */
    public function setBbFulType($bbFulType)
    {
        $this->bbFulType = $bbFulType;

        return $this;
    }

    /**
     * Get bbFulType
     *
     * @return string
     */
    public function getBbFulType()
    {
        return $this->bbFulType;
    }

    /**
     * Set bbFulSellerName
     *
     * @param string $bbFulSellerName
     *
     * @return RainforestProduct
     */
    public function setBbFulSellerName($bbFulSellerName)
    {
        $this->bbFulSellerName = $bbFulSellerName;

        return $this;
    }

    /**
     * Get bbFulSellerName
     *
     * @return string
     */
    public function getBbFulSellerName()
    {
        return $this->bbFulSellerName;
    }

    /**
     * Set bbPriceCurrency
     *
     * @param string $bbPriceCurrency
     *
     * @return RainforestProduct
     */
    public function setBbPriceCurrency($bbPriceCurrency)
    {
        $this->bbPriceCurrency = $bbPriceCurrency;

        return $this;
    }

    /**
     * Get bbPriceCurrency
     *
     * @return string
     */
    public function getBbPriceCurrency()
    {
        return $this->bbPriceCurrency;
    }

    /**
     * Set bbPriceAmount
     *
     * @param string $bbPriceAmount
     *
     * @return RainforestProduct
     */
    public function setBbPriceAmount($bbPriceAmount)
    {
        $this->bbPriceAmount = $bbPriceAmount;

        return $this;
    }

    /**
     * Get bbPriceAmount
     *
     * @return string
     */
    public function getBbPriceAmount()
    {
        return $this->bbPriceAmount;
    }
}
