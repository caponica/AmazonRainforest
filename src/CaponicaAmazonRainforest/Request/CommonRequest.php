<?php

namespace CaponicaAmazonRainforest\Request;

/**
 * Wrapper for the common parameters used when making any API request.
 *
 * @package CaponicaAmazonRainforest\Request
 */
abstract class CommonRequest implements CommonRequestInterface
{
    /**
     * @return array
     */
    abstract public function getQueryKeys();

    /**
     * Returns the value to send using the 'type' parameter when making this typo of API request
     *
     * @return string
     */
    abstract public function getQueryType();

    public function buildQueryArray($apiKey) {
        $queryArray = [
            'type'          => $this->getQueryType(),
            'api_key'       => $apiKey,
        ];

        foreach ($this->getQueryKeys() as $key) {
            if (isset($this->$key) && !is_null($this->$key)) {
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
    abstract public function getKeyLong();

    /**
     * A short form key, made by removing parts from the long key
     *
     * @return mixed
     */
    public function getKey() {
        return self::shortenKey($this->getKeyLong());
    }
    /**
     * Helper method to shorten a key by removing low-value parts of the string
     *
     * @param string $key
     * @return mixed
     */
    public static function shortenKey($key) {
        $key = preg_replace('/amazon\./',   '', $key, 1);
        $key = str_replace('https',     '', $key);
        $key = str_replace('http',      '', $key);
        $key = str_replace('://',       '', $key);
        $key = str_replace('www.',      '', $key);
        return $key;
    }
}