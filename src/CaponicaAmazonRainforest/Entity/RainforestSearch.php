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
 * Converts a SearchResponse into an object representing a search results page. Main fields have accessors, if you need something
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
        $this->setCategoryIdFilter($rfResponse->getReqParam('category_id'));
        $this->setUrl($rfResponse->getReqParam('url'));
        $this->setSortBy($rfResponse->getReqParam('sort_by'));

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
     * Set categoryIdFilter
     *
     * @param integer $categoryIdFilter
     *
     * @return RainforestSearch
     */
    public function setCategoryIdFilter($categoryIdFilter)
    {
        $this->categoryIdFilter = $categoryIdFilter;

        return $this;
    }

    /**
     * Get categoryIdFilter
     *
     * @return integer
     */
    public function getCategoryIdFilter()
    {
        return $this->categoryIdFilter;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return RainforestSearch
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set sortBy
     *
     * @param string $sortBy
     *
     * @return RainforestSearch
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    /**
     * Get sortBy
     *
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

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

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return RainforestSearch
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
     * @return RainforestSearch
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
