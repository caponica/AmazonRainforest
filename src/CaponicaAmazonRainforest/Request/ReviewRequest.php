<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestReviewList;
use CaponicaAmazonRainforest\Response\ReviewResponse;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

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

    protected ?string $amazon_domain = null;
    protected ?string $asin = null;
    protected ?string $url = null;

    protected ?string $gtin = null;
    protected null|bool|string $skip_gtin_cache = null;
    protected null|bool|string $global_reviews = null;
    protected null|bool|string $show_different_asins = null;
    protected ?string $search_term = null;
    protected ?string $review_id = null;
    protected ?int $max_page = 1;
    protected ?int $page = 1;

    protected ?string $reviewer_type = null; // see ENUM_REVIEWER_TYPE_XYZ
    protected ?string $review_stars = null; // see ENUM_REVIEW_STARS_XYZ
    protected ?string $review_formats = null; // see ENUM_REVIEW_FORMAT_XYZ
    protected ?string $review_media_type = null; // see ENUM_REVIEW_MEDIA_XYZ
    protected ?string $sort_by = null; // see ENUM_SORT_XYZ

    const OPTION_GTIN                       = 'gtin';
    const OPTION_SKIP_GTIN_CACHE            = 'skip_gtin_cache';
    const OPTION_GLOBAL_REVIEWS             = 'global_reviews';
    const OPTION_MAX_PAGE                   = 'max_page';
    const OPTION_PAGE                       = 'page';
    const OPTION_REVIEW_ID                  = 'review_id';
    const OPTION_SEARCH_TERM                = 'search_term';
    const OPTION_SHOW_DIFFERENT_ASINS       = 'show_different_asins';
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

    #[Pure] public function __construct($site_or_url, $asin=null, $options=[])
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

    public function getOptionKeys(): array
    {
        return [
            self::OPTION_GTIN,
            self::OPTION_SKIP_GTIN_CACHE,
            self::OPTION_GLOBAL_REVIEWS,
            self::OPTION_MAX_PAGE,
            self::OPTION_PAGE,
            self::OPTION_REVIEW_ID,
            self::OPTION_SEARCH_TERM,
            self::OPTION_SHOW_DIFFERENT_ASINS,
            self::OPTION_FILTER_REVIEWER_TYPE,
            self::OPTION_FILTER_REVIEW_STARS,
            self::OPTION_FILTER_REVIEW_FORMATS,
            self::OPTION_FILTER_REVIEW_MEDIA_TYPE,
            self::OPTION_FILTER_SORT_BY,
        ];
    }

    #[Pure] public function getFilterKeys(): array {
        return self::getStaticFilterKeys();
    }
    public static function getStaticFilterKeys(): array
    {
        return [
            self::OPTION_FILTER_REVIEWER_TYPE,
            self::OPTION_FILTER_REVIEW_STARS,
            self::OPTION_FILTER_REVIEW_FORMATS,
            self::OPTION_FILTER_REVIEW_MEDIA_TYPE,
            self::OPTION_FILTER_SORT_BY,
        ];
    }

    #[Pure] public function getQueryKeys(): array {
        $queryKeys = $this->getOptionKeys();
        $queryKeys[] = 'amazon_domain';
        $queryKeys[] = 'asin';
        $queryKeys[] = 'url';
        return $queryKeys;
    }

    public function getQueryType(): string
    {
        return RainforestClient::REQUEST_TYPE_REVIEWS;
    }

    #[ArrayShape(['requestClass' => "string", 'responseClass' => "string", 'entityClass' => "string", 'debug' => "string"])]
    public static function getReflectionArray(): array
    {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => ReviewResponse::CLASS_NAME,
            'entityClass'   => RainforestReviewList::CLASS_NAME,
            'debug'         => 'Review',
        ];
    }

    #[Pure] public function getFiltersString(): ?string
    {
        return self::convertFilterToString($this->getFiltersArray());
    }
    #[Pure] public function getFiltersArray(): array
    {
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
    private static function getConversionArrayFilterValueToChar(): array
    {
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
    #[Pure] public static function convertFilterToString($filterArray): ?string
    {
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
    public function getKeyLong(): string
    {
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