<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

/**
 * Simple object to encapsulate a site (marketplace) and an ASIN.
 * Used as a unique identifier when passing around other requests or objects.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class SiteAsin
{
    private $site;
    private $asin;

    /**
     * SiteAsin constructor.
     * @param string $site One of the RainforestClient::AMAZON_SITE_XYZ values
     * @param string $asin
     */
    public function __construct($site, $asin) {
        $this->site = $site;
        $this->asin = $asin;
    }

    public function __toString() {
        return $this->getKey();
    }

    public function getAsin() {
        return $this->asin;
    }

    public function getSite() {
        return $this->site;
    }

    /**
     * A unique key for this SiteAsin, based on a concatenation of site and asin.
     *
     * @return string
     */
    public function getKeyLong() {
        return $this->getSite() . '~' . $this->getAsin();
    }

    /**
     * A short form key, made by removing the word "amazon." from the long key
     * @return mixed
     */
    public function getKey() {
        return str_replace('amazon.', '', $this->getKeyLong());
    }
}
