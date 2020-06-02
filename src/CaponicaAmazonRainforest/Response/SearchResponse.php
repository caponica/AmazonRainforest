<?php

namespace CaponicaAmazonRainforest\Response;

/**
 * Wrapper for the raw response received back from a request to the Rainforest Search API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class SearchResponse extends CommonResponse
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Response\\SearchResponse';

    const MAIN_KEY_SEARCH_RESULTS   = 'search_results';
    const MAIN_KEY_PAGINATION       = 'pagination';

    private $searchResults = null;

    private $pagination = null;

    public function __construct($rfData)
    {
        parent::__construct($rfData);

        // main search data:
        $this->searchResults = &$this->data[self::MAIN_KEY_SEARCH_RESULTS];

        // occasional data:
        if (isset($this->data[self::MAIN_KEY_PAGINATION])) {
            $this->pagination = &$this->data[self::MAIN_KEY_PAGINATION];
        }
    }

    public static function getMainKeys() {
        $keys = parent::getMainKeys();
        $keys[] = self::MAIN_KEY_SEARCH_RESULTS;
        return $keys;
    }
    public static function getOccasionalKeys() {
        return [
            self::MAIN_KEY_PAGINATION,
        ];
    }

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