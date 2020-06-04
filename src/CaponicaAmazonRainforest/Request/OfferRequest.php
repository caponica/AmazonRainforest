<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestOfferList;
use CaponicaAmazonRainforest\Response\OfferResponse;

/**
 * Wrapper for the parameters used when making an Offers request.
 *
 * @package CaponicaAmazonRainforest\Request
 */
class OfferRequest extends CommonRequest
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Request\\OfferRequest';

    protected $amazon_domain;
    protected $asin;
    protected $url;

    protected $gtin;
    protected $page = 1;

    protected $offers_prime = null;
    protected $offers_free_shipping = null;
    protected $offers_condition_new = null;
    protected $offers_condition_used_like_new = null;
    protected $offers_condition_used_very_good = null;
    protected $offers_condition_used_good = null;
    protected $offers_condition_used_acceptable = null;

    const FILTER_PRIME                      = 'offers_prime';
    const FILTER_FREE_SHIPPING              = 'offers_free_shipping';
    const FILTER_CONDITION_NEW              = 'offers_condition_new';
    const FILTER_CONDITION_USED_LIKE_NEW    = 'offers_condition_used_like_new';
    const FILTER_CONDITION_USED_VERY_GOOD   = 'offers_condition_used_very_good';
    const FILTER_CONDITION_USED_GOOD        = 'offers_condition_used_good';
    const FILTER_CONDITION_USED_ACCEPTABLE  = 'offers_condition_used_acceptable';

    /**
     * OfferRequest constructor. If no second parameter is provided then the first parameter is assumed to be a full search URL.
     * If the second parameter is provided, or there's a gtin in options, then the first parameter is assumed to be the amazon_domain
     * The options parameter is an array using one or more of the keys returned by getOptionKeys()
     *
     * @param string $site_or_url
     * @param null|string $asin
     * @param array $options
     */
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
        $keys = $this->getFilterKeys();
        $keys[] = 'gtin';
        $keys[] = 'page';
        return $keys;
    }
    public function getFilterKeys() {
        return self::getStaticFilterKeys();
    }
    public static function getStaticFilterKeys() {
        return [
            self::FILTER_PRIME,
            self::FILTER_FREE_SHIPPING,
            self::FILTER_CONDITION_NEW,
            self::FILTER_CONDITION_USED_LIKE_NEW,
            self::FILTER_CONDITION_USED_VERY_GOOD,
            self::FILTER_CONDITION_USED_GOOD,
            self::FILTER_CONDITION_USED_ACCEPTABLE,
        ];
    }

    public static function getReflectionArray() {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => OfferResponse::CLASS_NAME,
            'entityClass'   => RainforestOfferList::CLASS_NAME,
            'debug'         => 'Offer',
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
        return RainforestClient::REQUEST_TYPE_OFFERS;
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
                $filters[$key] = true;
            } else {
                $filters[$key] = false;
            }
        }
        return $filters;
    }
    public static function convertFilterToString($filterArray) {
        if (empty($filterArray)) {
            return null;
        }
        $string = 'F';
        foreach (self::getStaticFilterKeys() as $key) {
            if (!isset($filterArray[$key])) {
                $string .= '_';
            } elseif (!empty($filterArray[$key])) {
                $string .= '1';
            } else {
                $string .= '0';
            }
        }
        return $string;
    }

    /**
     * A unique key for this OfferRequest
     *
     * @return string
     */
    public function getKeyLong() {
        if ($this->amazon_domain && $this->asin) {
            $key = $this->amazon_domain . '~' . $this->asin;
        } elseif ($this->url) {
            $key = $this->url;
        } else {
            throw new \InvalidArgumentException('Could not create a key for the OfferRequest - it must contain amazon_domain + asin, or an URL');
        }
        $key .= '~' . $this->getFiltersString() . '~' . $this->page;
        return $key;
    }
}