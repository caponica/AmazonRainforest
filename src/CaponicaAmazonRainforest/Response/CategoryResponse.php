<?php

namespace CaponicaAmazonRainforest\Response;

/**
 * Wrapper for the raw response received back from a request to the Rainforest Category API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class CategoryResponse extends CommonResponse
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Response\\CategoryResponse';

    const MAIN_KEY_CATEGORY_RESULTS     = 'category_results';
    const MAIN_KEY_PAGINATION           = 'pagination';

    private $categoryResults = null;

    private $pagination = null;

    public function __construct($rfData)
    {
        parent::__construct($rfData);

        // main category data:
        $this->categoryResults      = &$this->data[self::MAIN_KEY_CATEGORY_RESULTS];

        // occasional data:
        if (isset($this->data[self::MAIN_KEY_PAGINATION])) {
            $this->pagination       = &$this->data[self::MAIN_KEY_PAGINATION];
        }
    }

    public static function getMainKeys() {
        $keys = parent::getMainKeys();
        $keys[] = self::MAIN_KEY_CATEGORY_RESULTS;
        return $keys;
    }
    public static function getOccasionalKeys() {
        return [
            self::MAIN_KEY_PAGINATION,
        ];
    }

    public function getCategoryResult($key, $valueIfMissing=null) {
        if (empty($this->categoryResults[$key])) {
            return $valueIfMissing;
        }
        return $this->categoryResults[$key];
    }

    public function getCategoryResults() {
        return $this->categoryResults;
    }

    public function getPagination() {
        if (empty($this->pagination)) {
            return null;
        }
        return $this->pagination;
    }

    public function getCategoryResultCount() {
        if (empty($this->categoryResults)) {
            return null;
        }
        return count($this->categoryResults);
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