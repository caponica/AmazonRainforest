<?php

namespace CaponicaAmazonRainforest\Response;

/**
 * Wrapper for the common fields in all responses received back from requests to the Rainforest Product API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class CommonResponse
{
    const MAIN_KEY_REQUEST_INFO         = 'request_info';
    const MAIN_KEY_REQUEST_METADATA     = 'request_metadata';
    const MAIN_KEY_REQUEST_PARAMETERS   = 'request_parameters';

    protected $data = null;

    protected $reqInfo = null;
    protected $reqMeta = null;
    protected $reqParams = null;

    public function __construct($rfData) {
        $this->data = $rfData;

        // omnipresent data:
        $this->reqInfo   = &$this->data[self::MAIN_KEY_REQUEST_INFO];
        $this->reqMeta   = &$this->data[self::MAIN_KEY_REQUEST_METADATA];
        $this->reqParams = &$this->data[self::MAIN_KEY_REQUEST_PARAMETERS];
    }

    public static function getMainKeys() {
        return [
            self::MAIN_KEY_REQUEST_INFO,
            self::MAIN_KEY_REQUEST_METADATA,
            self::MAIN_KEY_REQUEST_PARAMETERS,
        ];
    }

    public function getReqInfo($key, $valueIfMissing=null) {
        if (empty($this->reqInfo[$key])) {
            return $valueIfMissing;
        }
        return $this->reqInfo[$key];
    }
    public function getReqMeta($key, $valueIfMissing=null) {
        if (empty($this->reqMeta[$key])) {
            return $valueIfMissing;
        }
        return $this->reqMeta[$key];
    }
    public function getReqParam($key, $valueIfMissing=null) {
        if (empty($this->reqParams[$key])) {
            return $valueIfMissing;
        }
        return $this->reqParams[$key];
    }

    /**
     * If all else fails and there's no other way to access a field you need then you can access the whole data tree, you
     * should normally be able to use one of the methods in the XyzResponse class to access the data you need, e.g. getProductField(x)
     *
     * @return array
     */
    public function getDataAsLastResort() {
        return $this->data;
    }

    public function getMarketplaceSuffix() {
        if (is_null($marketplaceDomain = $this->getReqParam('amazon_domain'))) {
            return null;
        }
        $dotPosition = strpos($marketplaceDomain, '.');
        if (1 > $dotPosition) {
            return null;
        }
        return substr($marketplaceDomain, $dotPosition+1);
    }
}