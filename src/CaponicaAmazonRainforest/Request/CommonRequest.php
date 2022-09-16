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
    abstract public function getQueryKeys(): array;

    /**
     * Returns the value to send using the 'type' parameter when making this typo of API request
     *
     * @return string
     */
    abstract public function getQueryType(): string;

    public function buildQueryArray($apiKey): array
    {
        $queryArray = [
            'type'          => $this->getQueryType(),
            'api_key'       => $apiKey,
        ];

        foreach ($this->getQueryKeys() as $key) {
            if (isset($this->$key) && !is_null($this->$key)) {
                if (is_bool($this->$key)) {
                    $this->$key = $this->$key ? 'true' : 'false';
                }
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
    abstract public function getKeyLong(): string;

    /**
     * A short form key, made by removing parts from the long key
     *
     * @return string
     */
    public function getKey(): string
    {
        return self::shortenKey($this->getKeyLong());
    }

    /**
     * Helper method to shorten a key by removing low-value parts of the string
     *
     * @param string $key
     * @return string
     */
    public static function shortenKey(string $key): string
    {
        $key = preg_replace('/amazon\./',   '', $key, 1);
        $key = str_replace('https',     '', $key);
        $key = str_replace('http',      '', $key);
        $key = str_replace('://',       '', $key);
        $key = str_replace('www.',      '', $key);
        return $key;
    }
}