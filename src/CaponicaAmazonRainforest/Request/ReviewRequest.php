<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestReview;
use CaponicaAmazonRainforest\Entity\RainforestReviewList;
use CaponicaAmazonRainforest\Response\ReviewResponse;

/**
 * Wrapper for the parameters used when making a Review request.
 * The constructor either takes:
 *      - url only
 *      - site and asin
 *      - site and no asin, but set a gtin in the options
 *
 * @package CaponicaAmazonRainforest\Request
 */
class ReviewRequest extends CommonRequest
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Request\\ReviewRequest';

    protected $amazon_domain = null;
    protected $asin = null;
    protected $url = null;

    protected $gtin = null;
    protected $search_term = null;
    protected $page = 1;

    protected $reviewer_type = null;
    protected $review_stars = null;
    protected $review_formats = null;
    protected $review_media_type = null;
    protected $sort_by = null;

    const OPTION_GTIN                       = 'gtin';
    const OPTION_SEARCH_TERM                = 'search_term';
    const OPTION_PAGE                       = 'page';
    const OPTION_FILTER_REVIEWER_TYPE       = 'reviewer_type';
    const OPTION_FILTER_REVIEW_STARS        = 'review_stars';
    const OPTION_FILTER_REVIEW_FORMATS      = 'review_formats';
    const OPTION_FILTER_REVIEW_MEDIA_TYPE   = 'review_media_type';
    const OPTION_FILTER_SORT_BY             = 'sort_by';

    const ENUM_REVIEWER_TYPE_VERIFIED       = 'verified_purchase';
    const ENUM_REVIEWER_TYPE_ALL            = 'all';
    const ENUM_REVIEW_STARS_ALL             = 'all_stars';
    const ENUM_REVIEW_STARS_5_STAR          = 'five_star';
    const ENUM_REVIEW_STARS_4_STAR          = 'four_star';
    const ENUM_REVIEW_STARS_3_STAR          = 'three_star';
    const ENUM_REVIEW_STARS_2_STAR          = 'two_star';
    const ENUM_REVIEW_STARS_1_STAR          = 'one_star';
    const ENUM_REVIEW_STARS_POSITIVE        = 'all_positive';
    const ENUM_REVIEW_STARS_CRITICAL        = 'all_critical';
    const ENUM_REVIEW_FORMAT_ALL            = 'all_formats';
    const ENUM_REVIEW_FORMAT_CURRENT        = 'current_format';
    const ENUM_REVIEW_MEDIA_ALL             = 'all_reviews';
    const ENUM_REVIEW_MEDIA_IMAGE_VIDEO     = 'media_reviews_only';
    const ENUM_SORT_MOST_HELPFUL            = 'most_helpful';
    const ENUM_SORT_MOST_RECENT             = 'most_recent';

    public function __construct($site_or_url, $asin=null, $options=[])
    {
        if (empty($asin) && empty($options['gtin'])) {
            $this->url = $site_or_url;
        } else {
            $this->amazon_domain = $site_or_url;
            if (!empty($options['gtin'])) {
                $this->gtin = $options['gtin'];
            } else {
                $this->asin = $asin;
            }
        }

        foreach ($this->getOptionKeys() as $key) {
            if (isset($options[$key])) {
                $this->$key = $options[$key];
            }
        }
    }

    public function getOptionKeys() {
        return [
            'gtin',
            'reviewer_type',
            'review_stars',
            'review_formats',
            'review_media_type',
            'sort_by',
            'search_term',
            'page',
        ];
    }

    public function getFilterKeys() {
        return self::getStaticFilterKeys();
    }
    public static function getStaticFilterKeys() {
        return [
            self::OPTION_FILTER_REVIEWER_TYPE,
            self::OPTION_FILTER_REVIEW_STARS,
            self::OPTION_FILTER_REVIEW_FORMATS,
            self::OPTION_FILTER_REVIEW_MEDIA_TYPE,
            self::OPTION_FILTER_SORT_BY,
        ];
    }

    public function getQueryKeys() {
        $queryKeys = $this->getOptionKeys();
        $queryKeys[] = 'amazon_domain';
        $queryKeys[] = 'asin';
        $queryKeys[] = 'url';
        return $queryKeys;
    }

    public function getQueryType() {
        return RainforestClient::REQUEST_TYPE_REVIEWS;
    }

    public static function getReflectionArray() {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => ReviewResponse::CLASS_NAME,
            'entityClass'   => RainforestReviewList::CLASS_NAME,
            'debug'         => 'Review',
        ];
    }

    public function getFiltersString() {
        return self::convertFilterToString($this->getFiltersArray());
    }
    public function getFiltersArray() {
        $filters = [];
        foreach ($this->getFilterKeys() as $key) {
            if (is_null($this->$key)) {
                continue;
            } elseif (!empty($this->$key)) {
                $filters[$key] = $this->$key;
            } else {
                $filters[$key] = false;
            }
        }
        return $filters;
    }
    private static function getConversionArrayFilterValueToChar() {
        return [
            self::ENUM_REVIEWER_TYPE_VERIFIED       => 'V',
            self::ENUM_REVIEWER_TYPE_ALL            => 'A',
            self::ENUM_REVIEW_STARS_ALL             => 'A',
            self::ENUM_REVIEW_STARS_5_STAR          => '5',
            self::ENUM_REVIEW_STARS_4_STAR          => '4',
            self::ENUM_REVIEW_STARS_3_STAR          => '3',
            self::ENUM_REVIEW_STARS_2_STAR          => '2',
            self::ENUM_REVIEW_STARS_1_STAR          => '1',
            self::ENUM_REVIEW_STARS_POSITIVE        => '+',
            self::ENUM_REVIEW_STARS_CRITICAL        => '-',
            self::ENUM_REVIEW_FORMAT_ALL            => 'A',
            self::ENUM_REVIEW_FORMAT_CURRENT        => 'C',
            self::ENUM_REVIEW_MEDIA_ALL             => 'A',
            self::ENUM_REVIEW_MEDIA_IMAGE_VIDEO     => 'R',
            self::ENUM_SORT_MOST_HELPFUL            => 'H',
            self::ENUM_SORT_MOST_RECENT             => 'R',
        ];
    }
    public static function convertFilterToString($filterArray) {
        if (empty($filterArray)) {
            return null;
        }

        $conversionArray = self::getConversionArrayFilterValueToChar();
        $string = 'F';

        foreach (self::getStaticFilterKeys() as $key) {
            if (!isset($filterArray[$key])) {
                $string .= '_';
            } elseif (!empty($filterArray[$key])) {
                if (!empty($conversionArray[$filterArray[$key]])) {
                    $string .= $conversionArray[$filterArray[$key]];
                } else {
                    $string .= '?';
                }
            } else {
                $string .= '0';
            }
        }
        return $string;
    }

    /**
     * A unique key for this ReviewRequest
     *
     * @return string
     */
    public function getKeyLong() {
        $key = null;
        if ($this->amazon_domain) {
            if ($this->asin) {
                $suffix = $this->asin;
            } elseif ($this->gtin) {
                $suffix = $this->gtin;
            } else {
                $suffix = '';
            }
            $key = $this->amazon_domain . '~' . $suffix;
        }
        if ($this->url) {
            $key = $this->url;
        }
        if (is_null($key)) {
            throw new \InvalidArgumentException('Could not create a key for the ReviewRequest - it must contain an Amazon Domain + ASIN/GTIN, or an URL');
        }

        $key .= '~' . $this->getFiltersString() . '~' . $this->page . '~' . $this->search_term;

        return $key;
    }
}