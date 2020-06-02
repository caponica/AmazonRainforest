<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestSearch;
use CaponicaAmazonRainforest\Response\SearchResponse;

/**
 * Wrapper for the parameters used when making a Search request.
 *
 * @package CaponicaAmazonRainforest\Request
 */
class SearchRequest extends CommonRequest
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Request\\SearchRequest';

    protected $amazon_domain;
    protected $search_term;
    protected $url;

    protected $category_id; // You can find the value to pass into the category_id field by inspecting an Amazon URL and looking for the 'rh=' querystring parameter.
    protected $page = 1;
    protected $sort_by;

    const SORT_BY_AVERAGE_REVIEW    = 'average_review';
    const SORT_BY_FEATURED          = 'featured';
    const SORT_BY_MOST_RECENT       = 'most_recent';
    const SORT_BY_PRICE_123         = 'price_low_to_high';
    const SORT_BY_PRICE_321         = 'price_high_to_low';

    /**
     * SearchRequest constructor. If no second parameter is provided then the first parameter is assumed to be a full search URL.
     * If the second parameter is provided then the first parameter is assumed to be the amazon_domain
     * The options parameter is an array using one or more of the keys returned by getOptionKeys()
     *
     * @param $site_or_url
     * @param null $search_term
     * @param array $options
     */
    public function __construct($site_or_url, $search_term=null, $options=[])
    {
        if (empty($search_term)) {
            $this->url = $site_or_url;
        } else {
            $this->amazon_domain = $site_or_url;
            $this->search_term = $search_term;
        }

        foreach ($this->getOptionKeys() as $key) {
            if (isset($options[$key])) {
                $this->$key = $options[$key];
            }
        }
    }

    public function getOptionKeys() {
        return [
            'category_id',
            'page',
            'sort_by',
        ];
    }

    public static function getReflectionArray() {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => SearchResponse::CLASS_NAME,
            'entityClass'   => RainforestSearch::CLASS_NAME,
            'debug'         => 'Search',
        ];
    }

    public function getQueryKeys() {
        $queryKeys = $this->getOptionKeys();
        $queryKeys[] = 'amazon_domain';
        $queryKeys[] = 'search_term';
        $queryKeys[] = 'url';
        return $queryKeys;
    }

    public function getQueryType() {
        return RainforestClient::REQUEST_TYPE_SEARCH;
    }

    /**
     * A unique key for this SearchRequest
     *
     * @return string
     */
    public function getKeyLong() {
        if ($this->amazon_domain && $this->search_term) {
            $suffix = '~' . $this->search_term;
            $suffix .= '~' . $this->category_id;
            $suffix .= '~' . $this->page;

            return $this->amazon_domain . $suffix;
        }
        if ($this->url) {
            return $this->url;
        }
        throw new \InvalidArgumentException('Could not create a key for the SearchRequest - it must contain amazon_domain + search_term, or an URL');
    }
}