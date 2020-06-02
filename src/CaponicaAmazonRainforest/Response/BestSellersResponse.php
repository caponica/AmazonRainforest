<?php

namespace CaponicaAmazonRainforest\Response;

use CaponicaAmazonRainforest\Entity\RainforestSearchResult;

/**
 * Wrapper for the raw response received back from a request to the Rainforest BestSeller API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class BestSellersResponse extends CommonResponse
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Response\\BestSellersResponse';

    const MAIN_KEY_BEST_SELLERS     = 'bestsellers';
    const MAIN_KEY_PAGINATION       = 'pagination';

    private $searchResults = null;

    private $pagination = null;

    public function __construct($rfData)
    {
        parent::__construct($rfData);

        // main result data:
        $this->searchResults = &$this->data[self::MAIN_KEY_BEST_SELLERS];

        // occasional data:
        if (isset($this->data[self::MAIN_KEY_PAGINATION])) {
            $this->pagination = &$this->data[self::MAIN_KEY_PAGINATION];
        }
    }

    public static function getMainKeys() {
        $keys = parent::getMainKeys();
        $keys[] = self::MAIN_KEY_BEST_SELLERS;
        return $keys;
    }
    public static function getOccasionalKeys() {
        return [
            self::MAIN_KEY_PAGINATION,
        ];
    }

    /**
     * Gets a single search result by index
     * @param int $key
     * @param null $valueIfMissing
     * @return RainforestSearchResult|null
     */
    public function getSearchResult($key, $valueIfMissing=null) {
        if (empty($this->searchResults[$key])) {
            return $valueIfMissing;
        }
        return $this->searchResults[$key];
    }
    public function getSearchResults() {
        return $this->searchResults;
    }
    public function getSearchResultCount() {
        if (empty($this->searchResults)) {
            return null;
        }
        return count($this->searchResults);
    }

    /**
     * Alias for getSearchResult()
     *
     * @see getSearchResult()
     * @param int $key
     * @param null $valueIfMissing
     * @return RainforestSearchResult|null
     */
    public function getBestSellerResult($key, $valueIfMissing=null) {
        return $this->getSearchResult($key, $valueIfMissing);
    }
    /**
     * Alias for getSearchResults()
     *
     * @see getSearchResults()
     */
    public function getBestSellerResults() {
        return $this->getSearchResults();
    }
    /**
     * Alias for getSearchResultCount()
     *
     * @see getSearchResultCount()
     */
    public function getBestSellerResultCount() {
        return $this->getSearchResultCount();
    }

    public function getPagination() {
        if (empty($this->pagination)) {
            return null;
        }
        return $this->pagination;
    }

    public function getCurrentPage() {
        if (empty($this->pagination) || empty($this->pagination['current_page'])) {
            return null;
        }
        return $this->pagination['current_page'];
    }
    public function getTotalPages() {
        if (empty($this->pagination) || empty($this->pagination['total_pages'])) {
            return null;
        }
        return $this->pagination['total_pages'];
    }
}