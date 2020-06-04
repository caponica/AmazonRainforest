<?php

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\CategoryResponse;
use CaponicaAmazonRainforest\Response\CommonResponse;

/**
 * Converts a ProductResponse into an object representing a product. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestCategory extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestCategory';

    /**
     * @param CommonResponse $rfResponse    A CategoryResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var CategoryResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);

        $this->setUrl($rfResponse->getReqParam('url'));
        $this->setSortBy($rfResponse->getReqParam('sort_by'));

        if ($rfResponse->getCategoryResultCount()) {
            $this->setPage($rfResponse->getCurrentPage());
            $this->setTotalPages($rfResponse->getTotalPages());

            foreach ($rfResponse->getCategoryResults() as $key => $categoryResultArray) {
                $this->addCategoryResultFromArray($categoryResultArray);
            }
        }
    }

    public function addCategoryResultFromArray($dataArray) {
        $this->categoryResults[] = new RainforestCategoryResult($dataArray);
    }

    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $sortBy = null;
    /**
     * @var int
     */
    protected $page;
    /**
     * @var int
     */
    protected $totalPages;
    /**
     * @var RainforestCategoryResult[]
     */
    protected $categoryResults = [];

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set sortBy
     *
     * @param string $sortBy
     *
     * @return RainforestCategory
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;

        return $this;
    }
    /**
     * Get sortBy
     *
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return RainforestCategory
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }
    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }
    /**
     * Set totalPages
     *
     * @param integer $totalPages
     *
     * @return RainforestCategory
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;

        return $this;
    }
    /**
     * Alias for getPage()
     * @return int
     */
    public function getCurrentPage() {
        return $this->getPage();
    }
    public function getTotalPages() {
        return $this->totalPages;
    }
    public function hasMorePages() {
        return $this->totalPages > $this->page;
    }

    public function getCategoryResults() {
        return $this->categoryResults;
    }
    public function getCategoryResult($index) {
        return $this->categoryResults[$index];
    }
    public function getCategoryResultCount() {
        return count($this->categoryResults);
    }
}
