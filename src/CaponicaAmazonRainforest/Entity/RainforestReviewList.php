<?php

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
    const EXPECTED_NUMBER_OF_REVIEWS_PER_PAGE = 10;

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

    public function isBrokenPage()
    {
        /*
         * Broken pages have:
         * "request_parameters": {
         *     "page": "44", // something greater than 1
         * },
         * "reviews": []
         * "pagination": {
         *     "reviews_total_filtered": 5113, // more than 10
         *     "reviews_total": 5113,
         *     "total_results": 5113,
         *     "total_pages": 512, // more than 1
         *     "current_page": 1,  // broken - should match request_parameters.page
         *     "start": 1,         // broken - should be (request_parameters.page-1)*10+1
         *     "end": 10           // broken - should be request_parameters.page*10 (or less if partial)
         * }
         */
        if (0 < $this->getReviewCount()) return false;

        /** @var ReviewResponse $response */
        $response = $this->getRainforestResponse();
        if ($response->getCurrentPage() > 1) return false;
        if ($response->getReqParam('page') > 1) return true;
        return false;
    }

    public function isFullPage() {
        return $this->getReviewCount() == static::getExpectedReviewsPerPage();
    }

    public function getExpectedReviewsPerPage() {
        return static::EXPECTED_NUMBER_OF_REVIEWS_PER_PAGE;
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
    protected string $asin;
    /**
     * Domain suffix, e.g. "co.uk", "com" or "de"
     *
     * @var string
     */
    protected string $marketplace;
    /**
     * @var array
     */
    protected array|null $filters;
    /**
     * @var string
     */
    protected string|null $searchTerm;
    /**
     * @var int
     */
    protected int|null $page = 0;
    /**
     * @var int
     */
    protected int|null $totalPages = 0;
    /**
     * @var RainforestReview[]
     */
    protected array $reviews = [];
    /**
     * @var RainforestReview
     */
    protected RainforestReview $topCriticalReview;
    /**
     * @var RainforestReview
     */
    protected RainforestReview $topPositiveReview;


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
