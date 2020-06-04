<?php

namespace CaponicaAmazonRainforest\Response;

use CaponicaAmazonRainforest\Request\OfferRequest;

/**
 * Wrapper for the raw response received back from a request to the Rainforest Offer API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class OfferResponse extends CommonResponse
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Response\\OfferResponse';

    const MAIN_KEY_OFFERS       = 'offers';
    const MAIN_KEY_PAGINATION   = 'pagination';
    const MAIN_KEY_PRODUCT      = 'product';


    private $offers = null;
    private $product = null;

    private $pagination = null;

    public function __construct($rfData)
    {
        parent::__construct($rfData);

        // main product data:
        $this->offers               = &$this->data[self::MAIN_KEY_OFFERS];
        $this->product              = &$this->data[self::MAIN_KEY_PRODUCT];

        // occasional data:
        if (isset($this->data[self::MAIN_KEY_PAGINATION])) {
            $this->pagination = &$this->data[self::MAIN_KEY_PAGINATION];
        }
    }

    public static function getMainKeys() {
        $keys = parent::getMainKeys();
        $keys[] = self::MAIN_KEY_OFFERS;
        $keys[] = self::MAIN_KEY_PRODUCT;
        return $keys;
    }
    public static function getOccasionalKeys() {
        return [
            self::MAIN_KEY_PAGINATION,
        ];
    }

    public function getProductField($key, $valueIfMissing=null) {
        if (empty($this->product[$key])) {
            return $valueIfMissing;
        }
        return $this->product[$key];
    }

    public function getOffers() {
        return $this->offers;
    }
    public function getOffer($key, $valueIfMissing=null) {
        if (empty($this->offers[$key])) {
            return $valueIfMissing;
        }
        return $this->offers[$key];
    }
    public function getOffersCount() {
        if (empty($this->offers)) {
            return null;
        }
        return count($this->offers);
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

    /**
     * Returns a semi-colon separated string of categories
     *
     * @return string|null
     */
    public function getCategoriesString() {
        $cats = $this->getProductField('categories');
        if (empty($cats)) {
            return null;
        }
        return implode(';', $cats);
    }
    public function getRating50() {
        return 10 * $this->getProductField('rating');
    }


    public function getAsin() {
        return $this->getProductField('asin');
    }
    public function getTitle() {
        return $this->getProductField('title');
    }
    public function getReviewsTotal() {
        return $this->getProductField('reviews_total');
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
    public function getAttributesArray() {
        return $this->getProductField('attributes');
    }

    public function getFiltersString() {
        return OfferRequest::convertFilterToString($this->getFiltersArray());
    }
    public function getFiltersArray() {
        $filters = [];
        foreach (OfferRequest::getStaticFilterKeys() as $key) {
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