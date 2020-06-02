<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestBestSellers;
use CaponicaAmazonRainforest\Response\BestSellersResponse;

/**
 * Wrapper for the parameters used when making a Search request.
 *
 * @package CaponicaAmazonRainforest\Request
 */
class BestSellersRequest extends CommonRequest
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Request\\BestSellersRequest';

    protected $url;
    protected $page = 1;
    protected $total_pages = null;

    /**
     * BestSellerRequest constructor.
     *
     * @param string $url
     * @param null|int $page
     * @param null|int $total_pages
     */
    public function __construct($url, $page=1, $total_pages=null)
    {
        $this->url = $url;
        if (!empty($page)) {
            $this->page = $page;
        }
        if (!empty($total_pages)) {
            $this->total_pages = $total_pages;
        }
    }

    public static function getReflectionArray() {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => BestSellersResponse::CLASS_NAME,
            'entityClass'   => RainforestBestSellers::CLASS_NAME,
            'debug'         => 'BestSeller',
        ];
    }

    public function getQueryKeys() {
        $queryKeys[] = 'url';
        $queryKeys[] = 'page';
        $queryKeys[] = 'total_pages';
        return $queryKeys;
    }

    public function getQueryType() {
        return RainforestClient::REQUEST_TYPE_BEST_SELLER;
    }

    /**
     * A unique key for this BestSellerRequest
     *
     * @return string
     */
    public function getKeyLong() {
        return $this->url . '~' . $this->page;
    }
}