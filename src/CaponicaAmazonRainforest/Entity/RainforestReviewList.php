<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Request\ReviewRequest;
use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Response\ReviewResponse;

/**
 * Converts a ReviewResponse into an object representing a reviews list page. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestReviewList extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestReviewList';

    /**
     * @param CommonResponse $rfResponse    A ReviewResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var ReviewResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);

        $this->setAsin($rfResponse->getAsin());
        $this->setMarketplace($rfResponse->getMarketplaceSuffix());
        $this->setFilters($rfResponse->getFiltersArray());
        $this->setSearchTerm($rfResponse->getReqParam('search_term'));

        if ($rfResponse->getReviewsCount()) {
            $this->setPage($rfResponse->getCurrentPage());
            $this->setTotalPages($rfResponse->getTotalPages());

            foreach ($rfResponse->getReviews() as $key => $reviewArray) {
                $this->addReviewFromArray($reviewArray);
            }

            if ($rfResponse->getTopCriticalReview()) {
                $this->setTopCriticalReviewFromArray($rfResponse->getTopCriticalReview());
            }
            if ($rfResponse->getTopPositiveReview()) {
                $this->setTopPositiveReviewFromArray($rfResponse->getTopPositiveReview());
            }
        }
    }

    public function addReviewFromArray($dataArray) {
        $this->reviews[] = new RainforestReview($dataArray);
    }
    public function setTopCriticalReviewFromArray($dataArray) {
        $this->topCriticalReview = new RainforestReview($dataArray);
    }
    public function setTopPositiveReviewFromArray($dataArray) {
        $this->topPositiveReview = new RainforestReview($dataArray);
    }

    /**
     * @var string
     */
    protected $asin;
    /**
     * Domain suffix, e.g. "co.uk", "com" or "de"
     *
     * @var string
     */
    protected $marketplace;
    /**
     * @var array
     */
    protected $filters;
    /**
     * @var string
     */
    protected $searchTerm;
    /**
     * @var int
     */
    protected $page;
    /**
     * @var int
     */
    protected $totalPages;
    /**
     * @var RainforestReview[]
     */
    protected $reviews = [];
    /**
     * @var RainforestReview
     */
    protected $topCriticalReview;
    /**
     * @var RainforestReview
     */
    protected $topPositiveReview;


    /**
     * Set asin
     *
     * @param string $asin
     *
     * @return RainforestReviewList
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;

        return $this;
    }
    /**
     * Set marketplace
     *
     * @param string $marketplace
     *
     * @return RainforestReviewList
     */
    public function setMarketplace($marketplace)
    {
        $this->marketplace = $marketplace;

        return $this;
    }
    /**
     * Set filters
     *
     * @param array $filters
     *
     * @return RainforestReviewList
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }
    /**
     * Set searchTerm
     *
     * @param string $searchTerm
     *
     * @return RainforestReviewList
     */
    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    /**
     * Get asin
     *
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }
    /**
     * Get marketplace
     *
     * @return string
     */
    public function getMarketplace()
    {
        return $this->marketplace;
    }
    public function getFilters() {
        return $this->filters;
    }
    public function getFiltersString() {
        return ReviewRequest::convertFilterToString($this->filters);
    }
    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return RainforestReviewList
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
     * @return RainforestReviewList
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

    public function getReviews() {
        return $this->reviews;
    }
    public function getReview($index) {
        return $this->reviews[$index];
    }
    public function getReviewCount() {
        return count($this->reviews);
    }

    public function getTopCriticalReview() {
        return $this->topCriticalReview;
    }
    public function getTopPositiveReview() {
        return $this->topPositiveReview;
    }
}
