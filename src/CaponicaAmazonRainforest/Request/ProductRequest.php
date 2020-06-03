<?php

namespace CaponicaAmazonRainforest\Request;

use CaponicaAmazonRainforest\Client\RainforestClient;
use CaponicaAmazonRainforest\Entity\RainforestProduct;
use CaponicaAmazonRainforest\Response\ProductResponse;

/**
 * Wrapper for the parameters used when making a Product request.
 * The constructor either takes:
 *      - url only
 *      - site and asin
 *      - site and no asin, but set a gtin in the options
 *
 * @package CaponicaAmazonRainforest\Request
 */
class ProductRequest extends CommonRequest
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Request\\ProductRequest';

    protected $amazon_domain;
    protected $asin;
    protected $url;

    protected $gtin;
    protected $include_summarization_attributes;
    protected $include_a_plus_body;
    protected $device;

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
            'include_summarization_attributes',
            'include_a_plus_body',
            'device',
        ];
    }

    public static function getReflectionArray() {
        return [
            'requestClass'  => self::CLASS_NAME,
            'responseClass' => ProductResponse::CLASS_NAME,
            'entityClass'   => RainforestProduct::CLASS_NAME,
            'debug'         => 'Product',
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
        return RainforestClient::REQUEST_TYPE_PRODUCT;
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
}