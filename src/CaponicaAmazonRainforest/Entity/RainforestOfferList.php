<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Request\OfferRequest;
use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Response\OfferResponse;

/**
 * Converts an OfferResponse into an object representing an offers list page. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestOfferList extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestOfferList';

    /**
     * @param CommonResponse $rfResponse    A OfferResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var OfferResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);

        $this->setAsin($rfResponse->getAsin());
        $this->setMarketplace($rfResponse->getMarketplaceSuffix());
        $this->setFilters($rfResponse->getFiltersArray());

        if ($rfResponse->getOffersCount()) {
            $this->setPage($rfResponse->getCurrentPage());
            $this->setTotalPages($rfResponse->getTotalPages());

            foreach ($rfResponse->getOffers() as $key => $offerArray) {
                $this->addOfferFromArray($offerArray);
            }
        }
    }

    public function addOfferFromArray($dataArray) {
        $this->offers[] = new RainforestOffer($dataArray);
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
     * @var array
     */
    protected $filters;
    /**
     * @var int
     */
    protected $page;
    /**
     * @var int
     */
    protected $totalPages;
    /**
     * @var RainforestOffer[]
     */
    protected $offers = [];


    /**
     * Set asin
     *
     * @param string $asin
     *
     * @return RainforestOfferList
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
     * @return RainforestOfferList
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
     * Set filters
     *
     * @param array $filters
     *
     * @return RainforestOfferList
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }
    public function getFilters() {
        return $this->filters;
    }
    public function getFiltersString() {
        return OfferRequest::convertFilterToString($this->filters);
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return RainforestOfferList
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }
    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }
    /**
     * Set totalPages
     *
     * @param integer $totalPages
     *
     * @return RainforestOfferList
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;

        return $this;
    }
    /**
     * Alias for getPage()
     * @return int
     */
    public function getCurrentPage() {
        return $this->getPage();
    }
    public function getTotalPages() {
        return $this->totalPages;
    }
    public function hasMorePages() {
        return $this->totalPages > $this->page;
    }

    public function getOffers() {
        return $this->offers;
    }
    public function getOffer($index) {
        return $this->offers[$index];
    }
    public function getOfferCount() {
        return count($this->offers);
    }
}
