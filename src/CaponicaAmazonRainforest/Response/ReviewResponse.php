<?php

namespace CaponicaAmazonRainforest\Response;

use CaponicaAmazonRainforest\Request\ReviewRequest;

/**
 * Wrapper for the raw response received back from a request to the Rainforest Review API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class ReviewResponse extends CommonResponse
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Response\\ReviewResponse';

    const MAIN_KEY_REVIEWS      = 'reviews';
    const MAIN_KEY_PAGINATION   = 'pagination';
    const MAIN_KEY_PRODUCT      = 'product';
    const MAIN_KEY_SUMMARY      = 'summary';
    const MAIN_KEY_TOP_CRITICAL = 'top_critical';
    const MAIN_KEY_TOP_POSITIVE = 'top_positive';

    private $reviews = null;
    private $product = null;

    private $pagination     = null;
    private $summary        = null;
    private $top_positive   = null;
    private $top_critical   = null;

    public function __construct($rfData)
    {
        parent::__construct($rfData);

        // main review data:
        $this->reviews              = &$this->data[self::MAIN_KEY_REVIEWS];
        $this->product              = &$this->data[self::MAIN_KEY_PRODUCT];

        // occasional data:
        if (isset($this->data[self::MAIN_KEY_PAGINATION])) {
            $this->pagination = &$this->data[self::MAIN_KEY_PAGINATION];
        }
        if (isset($this->data[self::MAIN_KEY_SUMMARY])) {
            $this->summary = &$this->data[self::MAIN_KEY_SUMMARY];
        }
        if (isset($this->data[self::MAIN_KEY_TOP_CRITICAL])) {
            $this->top_critical = &$this->data[self::MAIN_KEY_TOP_CRITICAL];
        }
        if (isset($this->data[self::MAIN_KEY_TOP_POSITIVE])) {
            $this->top_positive = &$this->data[self::MAIN_KEY_TOP_POSITIVE];
        }
    }

    public static function getMainKeys() {
        $keys = parent::getMainKeys();
        $keys[] = self::MAIN_KEY_REVIEWS;
        $keys[] = self::MAIN_KEY_PRODUCT;
        return $keys;
    }
    public static function getOccasionalKeys() {
        return [
            self::MAIN_KEY_PAGINATION,
            self::MAIN_KEY_SUMMARY,
            self::MAIN_KEY_TOP_CRITICAL,
            self::MAIN_KEY_TOP_POSITIVE,
        ];
    }

    public function getProductField($key, $valueIfMissing=null) {
        if (empty($this->product[$key])) {
            return $valueIfMissing;
        }
        return $this->product[$key];
    }
    public function getSummaryField($key, $valueIfMissing=null) {
        if (empty($this->summary[$key])) {
            return $valueIfMissing;
        }
        return $this->summary[$key];
    }
    public function getTopCriticalField($key, $valueIfMissing=null) {
        if (empty($this->top_critical[$key])) {
            return $valueIfMissing;
        }
        return $this->top_critical[$key];
    }
    public function getTopPositiveField($key, $valueIfMissing=null) {
        if (empty($this->top_positive[$key])) {
            return $valueIfMissing;
        }
        return $this->top_positive[$key];
    }

    public function getReviews() {
        return $this->reviews;
    }
    public function getReview($key, $valueIfMissing=null) {
        if (empty($this->reviews[$key])) {
            return $valueIfMissing;
        }
        return $this->reviews[$key];
    }
    public function getReviewsCount() {
        if (empty($this->reviews)) {
            return null;
        }
        return count($this->reviews);
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
    public function getTopCriticalReview($valueIfMissing=null) {
        if (empty($this->top_critical)) {
            return $valueIfMissing;
        }
        return $this->top_critical;
    }
    public function getTopPositiveReview($valueIfMissing=null) {
        if (empty($this->top_positive)) {
            return $valueIfMissing;
        }
        return $this->top_positive;
    }

    public function getAsin() {
        return $this->getProductField('asin');
    }
    public function getProductTitle() {
        return $this->getProductField('title');
    }
    public function getProductLink() {
        return $this->getProductField('link');
    }
    public function getProductIsPrime() {
        return $this->getProductField('is_prime');
    }
    public function getImageLink() {
        return $this->getProductField('image');
    }
    public function getSubTitleText() {
        if ($subTitle = $this->getProductField('sub_title')) {
            if (!empty($subTitle['text'])) {
                return $subTitle['text'];
            }
        }
        return null;
    }
    public function getSubTitleLink() {
        if ($subTitle = $this->getProductField('sub_title')) {
            if (!empty($subTitle['link'])) {
                return $subTitle['link'];
            }
        }
        return null;
    }
    public function getAttributesArray() {
        return $this->getProductField('attributes');
    }
    public function getPriceCurrency() {
        if ($price = $this->getProductField('price')) {
            if (!empty($price['currency'])) {
                return $price['currency'];
            }
        }
        return null;
    }
    public function getPriceAmount() {
        if ($price = $this->getProductField('price')) {
            if (!empty($price['value'])) {
                return $price['value'];
            }
        }
        return null;
    }
    public function getShippingRaw() {
        if ($shipping = $this->getProductField('shipping')) {
            if (!empty($shipping['raw'])) {
                return $shipping['raw'];
            }
        }
        return null;
    }

    public function getRating50() {
        return 10 * $this->getSummaryField('rating');
    }
    public function getRatingsTotal() {
        return $this->getProductField('ratings_total');
    }
    public function getReviewsTotal() {
        return $this->getProductField('reviews_total');
    }
    public function getReviewsPositiveTotal() {
        return $this->getProductField('reviews_positive');
    }
    public function getReviewsCriticalTotal() {
        return $this->getProductField('reviews_critical');
    }
    public function getReviewsBreakdown() {
        return $this->getProductField('reviews_breakdown');
    }

    public function getFiltersString() {
        return ReviewRequest::convertFilterToString($this->getFiltersArray());
    }
    public function getFiltersArray() {
        $filters = [];
        foreach (ReviewRequest::getStaticFilterKeys() as $key) {
            $paramValue = $this->getReqParam($key);
            if (is_null($paramValue)) {
                continue;
            } elseif (!empty($paramValue)) {
                $filters[$key] = true;
            } else {
                $filters[$key] = false;
            }
        }
        return $filters;
    }
}