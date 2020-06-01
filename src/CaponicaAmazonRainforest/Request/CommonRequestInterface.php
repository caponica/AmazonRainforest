<?php

namespace CaponicaAmazonRainforest\Request;

/**
 * Wrapper for the common parameters used when making any API request.
 *
 * @package CaponicaAmazonRainforest\Request
 */
interface CommonRequestInterface
{
    /**
     * Returns an array of namespaced class/function names used when handling this kind of Request and building the
     * resulting Response and Entity objects. Keys should be: 'requestClass', 'responseClass', 'entityClass', 'debug'
     *
     * @return array
     */
    public static function getReflectionArray();

    public function getQueryKeys();

    /**
     * @return string
     */
    public function getQueryType();

    public function buildQueryArray($apiKey);

    /**
     * A unique key for this ProductRequest
     *
     * @return string
     */
    public function getKeyLong();

    /**
     * A short form key, made by removing parts from the long key
     *
     * @return mixed
     */
    public function getKey();
}