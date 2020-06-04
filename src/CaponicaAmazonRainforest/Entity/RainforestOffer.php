<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

/**
 * A child object which lives under RainforestOfferList. Represents a single "hit" in the OfferList. You can access the
 * original data array used to build the object via getOriginalDataArray();
 *
 * @see  https://rainforestapi.com/docs/product-data-api/results/offers
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestOffer
{
    /**
     * @param array|null $data
     */
    public function __construct($data = null) {
        if (!empty($data)) {
            $this->updateFromDataArray($data);
        }
    }

    /**
     * The original data array used to build this object - in case you need something not cleanly converted
     *
     * @var array $data
     */
    protected $data;

    protected $isPrime;
    protected $priceCurrency;
    protected $priceAmount;
    protected $importFeeCurrency;
    protected $importFeeAmount;
    protected $deliveryCurrency;
    protected $deliveryAmount;
    protected $deliveryIsFree;
    protected $deliveryIsFba;
    protected $deliveryIsShippedCrossBorder;
    protected $deliveryComments;
    protected $conditionIsNew;
    protected $conditionTitle;
    protected $conditionComments;
    protected $sellerName;
    protected $sellerLink;
    protected $sellerRating50;
    protected $sellerRatingsTotal;
    protected $sellerPositiveRatings100;

    /**
     * @param array $data       Raw array of data from the API
     */
    public function updateFromDataArray($data) {
        $this->setData($data);

        if (!empty($data['is_prime'])) {
            $this->setIsPrime(true);
        }

        if (!empty($data['price'])) {
            if (!empty($data['price']['currency'])) {
                $this->setPriceCurrency($data['price']['currency']);
            }
            if (!empty($data['price']['value'])) {
                $this->setPriceAmount($data['price']['value']);
            }
        }
        if (!empty($data['import_fee'])) { // @todo - check if this is the correct key for import fee data
            if (!empty($data['import_fee']['currency'])) {
                $this->setImportFeeCurrency($data['import_fee']['currency']);
            }
            if (!empty($data['import_fee']['value'])) {
                $this->setImportFeeAmount($data['import_fee']['value']);
            }
        }
        if (!empty($data['delivery'])) {
            if (!empty($data['delivery']['currency'])) {
                $this->setDeliveryCurrency($data['delivery']['currency']);
            }
            if (!empty($data['delivery']['value'])) {
                $this->setDeliveryAmount($data['delivery']['value']);
            }

            if (!empty($data['delivery']['is_free'])) {
                $this->setDeliveryIsFree(true);
            }
            if (!empty($data['delivery']['fulfilled_by_amazon'])) {
                $this->setDeliveryIsFba(true);
            }
            if (!empty($data['delivery']['shipped_from_outside_country'])) {
                $this->setDeliveryIsShippedCrossBorder(true);
            }
            if (!empty($data['delivery']['comments'])) {
                $this->setDeliveryComments($data['delivery']['comments']);
            }
        }
        if (!empty($data['condition'])) {
            if (!empty($data['condition']['is_new'])) {
                $this->setConditionIsNew(true);
            }
            if (!empty($data['condition']['title'])) {
                $this->setConditionTitle($data['condition']['title']);
            }
            if (!empty($data['condition']['comments'])) {
                $this->setConditionComments($data['condition']['comments']);
            }
        }
        if (!empty($data['seller'])) {
            if (!empty($data['seller']['name'])) {
                $this->setSellerName($data['seller']['name']);
            }
            if (!empty($data['seller']['link'])) {
                $this->setSellerLink($data['seller']['link']);
            }
            if (!empty($data['seller']['rating'])) {
                $this->setSellerRating50(10 * $data['seller']['rating']);
            }
            if (!empty($data['seller']['ratings_total'])) {
                $this->setSellerRatingsTotal($data['seller']['ratings_total']);
            }
            if (!empty($data['seller']['ratings_percentage_positive'])) {
                $this->setSellerPositiveRatings100($data['seller']['ratings_percentage_positive']);
            }
        }
    }

    public function getOriginalDataArray() {
        return $this->data;
    }
    protected function setData($value) {
        $this->data = $value;
        return $this;
    }

    public function setIsPrime($value) {
        $this->isPrime = $value;
        return $this;
    }
    public function setPriceCurrency($value) {
        $this->priceCurrency = $value;
        return $this;
    }
    public function setPriceAmount($value) {
        $this->priceAmount = $value;
        return $this;
    }
    public function setImportFeeCurrency($value) {
        $this->importFeeCurrency = $value;
        return $this;
    }
    public function setImportFeeAmount($value) {
        $this->importFeeAmount = $value;
        return $this;
    }
    public function setConditionIsNew($value) {
        $this->conditionIsNew = $value;
        return $this;
    }
    public function setConditionTitle($value) {
        $this->conditionTitle = $value;
        return $this;
    }
    public function setConditionComments($value) {
        $this->conditionComments = $value;
        return $this;
    }
    public function setDeliveryIsFree($value) {
        $this->deliveryIsFree = $value;
        return $this;
    }
    public function setDeliveryIsFba($value) {
        $this->deliveryIsFba = $value;
        return $this;
    }
    public function setDeliveryIsShippedCrossBorder($value) {
        $this->deliveryIsShippedCrossBorder = $value;
        return $this;
    }
    public function setDeliveryComments($value) {
        $this->deliveryComments = $value;
        return $this;
    }
    public function setDeliveryCurrency($value) {
        $this->deliveryCurrency = $value;
        return $this;
    }
    public function setDeliveryAmount($value) {
        $this->deliveryAmount = $value;
        return $this;
    }
    public function setSellerName($value) {
        $this->sellerName = $value;
        return $this;
    }
    public function setSellerLink($value) {
        $this->sellerLink = $value;
        return $this;
    }
    public function setSellerRating50($value) {
        $this->sellerRating50 = $value;
        return $this;
    }
    public function setSellerRatingsTotal($value) {
        $this->sellerRatingsTotal = $value;
        return $this;
    }
    public function setSellerPositiveRatings100($value) {
        $this->sellerPositiveRatings100 = $value;
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
     * Get priceCurrency
     *
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
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
     * Get importFeeCurrency
     *
     * @return string
     */
    public function getImportFeeCurrency()
    {
        return $this->importFeeCurrency;
    }
    /**
     * Get importFeeAmount
     *
     * @return string
     */
    public function getImportFeeAmount()
    {
        return $this->importFeeAmount;
    }
    /**
     * Get conditionIsNew
     *
     * @return boolean
     */
    public function getConditionIsNew()
    {
        return $this->conditionIsNew;
    }
    /**
     * Get conditionTitle
     *
     * @return string
     */
    public function getConditionTitle()
    {
        return $this->conditionTitle;
    }
    /**
     * Get conditionComments
     *
     * @return string
     */
    public function getConditionComments()
    {
        return $this->conditionComments;
    }
    /**
     * Get deliveryIsFree
     *
     * @return boolean
     */
    public function getDeliveryIsFree()
    {
        return $this->deliveryIsFree;
    }
    /**
     * Get deliveryIsFba
     *
     * @return boolean
     */
    public function getDeliveryIsFba()
    {
        return $this->deliveryIsFba;
    }
    /**
     * Get deliveryIsShippedCrossBorder
     *
     * @return boolean
     */
    public function getDeliveryIsShippedCrossBorder()
    {
        return $this->deliveryIsShippedCrossBorder;
    }
    /**
     * Get deliveryComments
     *
     * @return string
     */
    public function getDeliveryComments()
    {
        return $this->deliveryComments;
    }
    /**
     * Get deliveryCurrency
     *
     * @return string
     */
    public function getDeliveryCurrency()
    {
        return $this->deliveryCurrency;
    }
    /**
     * Get deliveryAmount
     *
     * @return string
     */
    public function getDeliveryAmount()
    {
        return $this->deliveryAmount;
    }
    /**
     * Get sellerName
     *
     * @return string
     */
    public function getSellerName()
    {
        return $this->sellerName;
    }
    /**
     * Get sellerLink
     *
     * @return string
     */
    public function getSellerLink()
    {
        return $this->sellerLink;
    }
    /**
     * Get sellerRating50
     *
     * @return integer
     */
    public function getSellerRating50()
    {
        return $this->sellerRating50;
    }
    /**
     * Get sellerRatingsTotal
     *
     * @return integer
     */
    public function getSellerRatingsTotal()
    {
        return $this->sellerRatingsTotal;
    }
    /**
     * Get sellerPositiveRatings100
     *
     * @return integer
     */
    public function getSellerPositiveRatings100()
    {
        return $this->sellerPositiveRatings100;
    }
}
