<?php

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\BestSellersResponse;
use CaponicaAmazonRainforest\Response\CommonResponse;

/**
 * Converts a BestSellersResponse into an object representing the data. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestBestSellers extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestBestSellers';

    /**
     * @param CommonResponse $rfResponse    A BestSellersResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var BestSellersResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);

        $this->setUrl($rfResponse->getReqParam('url'));

        if ($rfResponse->getSearchResultCount()) {
            $this->setPage($rfResponse->getCurrentPage());
            $this->setTotalPages($rfResponse->getTotalPages());

            foreach ($rfResponse->getSearchResults() as $key => $searchResultArray) {
                $this->addSearchResultFromArray($searchResultArray);
            }
        }
    }

    public function addSearchResultFromArray($dataArray) {
        $this->searchResults[] = new RainforestSearchResult($dataArray);
    }

    /**
     * @var string
     */
    protected $url;
    /**
     * @var int
     */
    protected $page;
    /**
     * @var int
     */
    protected $totalPages;
    /**
     * @var RainforestSearchResult[]
     */
    protected $searchResults = [];

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
    public function getUrl() {
        return $this->url;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }
    public function getPage()
    {
        return $this->page;
    }
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

    public function getSearchResults() {
        return $this->getBestSellerResults();
    }
    public function getSearchResult($index) {
        return $this->getBestSellerResult($index);
    }
    public function getSearchResultCount() {
        return $this->getBestSellerResultCount();
    }

    /**
     * Alias for getSearchResults()
     *
     * @see getSearchResults()
     * @return RainforestSearchResult[]
     */
    public function getBestSellerResults() {
        return $this->getSearchResults();
    }
    /**
     * Alias for getSearchResult()
     *
     * @see getSearchResult()
     * @param int $index
     * @return RainforestSearchResult
     */
    public function getBestSellerResult($index) {
        return $this->getSearchResult($index);
    }
    /**
     * Alias for getSearchResultCount()
     *
     * @see getSearchResultCount()
     * @return int
     */
    public function getBestSellerResultCount() {
        return $this->getSearchResultCount();
    }
}
