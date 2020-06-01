<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestCategory;
use CaponicaAmazonRainforest\Response\CategoryResponse;

/**
 * Wrapper for the parameters used when making a Category request.
 *
 * @package CaponicaAmazonRainforest\Request
 */
class CategoryRequest extends CommonRequest
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Request\\CategoryRequest';

    protected $url;
    protected $page = 1;
    protected $sort_by;

    const SORT_BY_AVERAGE_REVIEW    = 'average_review';
    const SORT_BY_FEATURED          = 'featured';
    const SORT_BY_MOST_RECENT       = 'most_recent';
    const SORT_BY_PRICE_123         = 'price_low_to_high';
    const SORT_BY_PRICE_321         = 'price_high_to_low';

    public function __construct($url, $page=1, $sort_by=self::SORT_BY_FEATURED)
    {
        $this->url = $url;
        $this->page = $page;
        $this->sort_by = $sort_by;
    }

    public static function getReflectionArray() {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => CategoryResponse::CLASS_NAME,
            'entityClass'   => RainforestCategory::CLASS_NAME,
            'debug'         => 'Category',
        ];
    }

    public function getQueryKeys() {
        $queryKeys[] = 'url';
        $queryKeys[] = 'page';
        $queryKeys[] = 'sort_by';
        return $queryKeys;
    }

    public function getQueryType() {
        return RainforestClient::REQUEST_TYPE_CATEGORY;
    }

    /**
     * A unique key for this ProductRequest
     *
     * @return string
     */
    public function getKeyLong() {
        return $this->url . '~' . $this->sort_by . '~' . $this->page;
    }
}