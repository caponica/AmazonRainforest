<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;

/**
 * Wrapper for the parameters used when making a Product request.
 * The constructor either takes:
 *      - url only
 *      - site and asin
 *      - site and no asin, but set a gtin in the options
 *
 * @package CaponicaAmazonRainforest\Request
 */
class ProductRequest
{
    private $amazon_domain;
    private $asin;
    private $url;

    private $gtin;
    private $include_summarization_attributes;
    private $include_a_plus_body;
    private $device;

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

        foreach (self::getOptionKeys() as $key) {
            if (isset($options[$key])) {
                $this->$key = $options[$key];
            }
        }
    }

    public static function getOptionKeys() {
        return [
            'gtin',
            'include_summarization_attributes',
            'include_a_plus_body',
            'device',
        ];
    }
    public static function getQueryKeys() {
        $queryKeys = self::getOptionKeys();
        $queryKeys[] = 'amazon_domain';
        $queryKeys[] = 'asin';
        $queryKeys[] = 'gtin';
        return $queryKeys;
    }

    public function buildQueryArray($apiKey) {
        $queryArray = [
            'type'          => RainforestClient::REQUEST_TYPE_PRODUCT,
            'api_key'       => $apiKey,
        ];

        foreach (self::getQueryKeys() as $key) {
            if (isset($this->$key)) {
                $queryArray[$key] = $this->$key;
            }
        }

        return $queryArray;
    }

    /**
     * A unique key for this ProductRequest
     *
     * @return string
     */
    public function getKeyLong() {
        if ($this->amazon_domain) {
            if ($this->asin) {
                $suffix = $this->asin;
            } elseif ($this->gtin) {
                $suffix = $this->gtin;
            } else {
                $suffix = '';
            }
            return $this->amazon_domain . '~' . $suffix;
        }
        if ($this->url) {
            return $this->url;
        }
        throw new \InvalidArgumentException('Could not create a key for the ProductRequest - it must contain an Amazon Domain + ASIN/GTIN, or an URL');
    }

    /**
     * A short form key, made by removing parts from the long key
     *
     * @return mixed
     */
    public function getKey() {
        $key = str_replace('amazon.',   '', $this->getKeyLong());
        $key = str_replace('https',     '', $key);
        $key = str_replace('http',      '', $key);
        $key = str_replace('://',       '', $key);
        return $key;
    }
}