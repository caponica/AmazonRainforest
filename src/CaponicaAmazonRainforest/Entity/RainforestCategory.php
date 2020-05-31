<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

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
     * @param CommonResponse $rfResponse    A ProductResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var CategoryResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);

        $this->url = $rfResponse->getReqParam('url');
        $this->sortBy = $rfResponse->getReqParam('sort_by');

        if ($rfResponse->getCategoryResultCount()) {
            $this->page = $rfResponse->getCurrentPage();
            $this->totalPages = $rfResponse->getTotalPages();

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

    public function getCurrentPage() {
        return $this->page;
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
