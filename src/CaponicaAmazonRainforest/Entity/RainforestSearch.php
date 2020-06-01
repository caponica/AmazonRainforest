<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Response\SearchResponse;

/**
 * Converts a ProductResponse into an object representing a product. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestSearch extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestSearch';

    /**
     * @param CommonResponse $rfResponse    A SearchResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var SearchResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);

        $this->setSearchTerm($rfResponse->getReqParam('search_term'));
        $this->setMarketplace($rfResponse->getMarketplaceSuffix());
        $this->categoryIdFilter = $rfResponse->getReqParam('category_id');
        $this->url = $rfResponse->getReqParam('url');
        $this->sortBy = $rfResponse->getReqParam('sort_by');

        if ($rfResponse->getSearchResultCount()) {
            $this->page = $rfResponse->getCurrentPage();
            $this->totalPages = $rfResponse->getTotalPages();

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
    protected $searchTerm;
    /**
     * Domain suffix, e.g. "co.uk", "com" or "de"
     *
     * @var string
     */
    protected $marketplace;
    /**
     * @var int
     */
    protected $categoryIdFilter;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $sortBy = null;
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

    /**
     * Set searchTerm
     *
     * @param string $value
     *
     * @return RainforestSearch
     */
    public function setSearchTerm($value)
    {
        $this->searchTerm = $value;

        return $this;
    }

    /**
     * Get searchTerm
     *
     * @return string
     */
    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * Set marketplace
     *
     * @param string $marketplace
     *
     * @return RainforestSearch
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

    public function getCurrentPage() {
        return $this->page;
    }
    public function getTotalPages() {
        return $this->totalPages;
    }
    public function hasMorePages() {
        return $this->totalPages > $this->page;
    }

    public function getSearchResults() {
        return $this->searchResults;
    }
    public function getSearchResult($index) {
        return $this->searchResults[$index];
    }
    public function getSearchResultCount() {
        return count($this->searchResults);
    }
}
