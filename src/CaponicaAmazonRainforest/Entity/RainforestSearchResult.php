<?php

namespace CaponicaAmazonRainforest\Entity;

/**
 * A child object which lives under RainforestSearch. Represents a single "hit" in the Search results.
 * @see  https://rainforestapi.com/docs/product-data-api/results/search
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestSearchResult
{
    /**
     * @param array|null $data
     */
    public function __construct($data = null) {
        if (!empty($data)) {
            $this->updateFromSearchResultArray($data);
        }
    }

    protected $position;
    protected $title;
    protected $asin;
    protected $link;
    protected $isPrime;
    protected $image;
    protected $sponsored;
    protected $addOnItem;
    protected $categories;
    protected $availability;
    protected $rating50;
    protected $ratingsTotal;
    protected $reviewsTotal;
    protected $priceCurrency;
    protected $priceAmount;

    /**
     * @param array $data       Raw array of data from the API
     */
    public function updateFromSearchResultArray($data) {
        $fields = [
            'position'      => 'setPosition',
            'title'         => 'setTitle',
            'asin'          => 'setAsin',
            'link'          => 'setLink',
            'image'         => 'setImage',
            'sponsored'     => 'setSponsored',
            'categories'    => 'setCategories',
            'is_prime'      => 'setIsPrime',
            'ratings_total' => 'setRatingsTotal',
            'reviews_total' => 'setReviewsTotal',
            'rating'        => 'setRating50FromRating5',
        ];
        foreach ($fields as $dataKey => $setter) {
            if (isset($data[$dataKey])) {
                $this->$setter($data[$dataKey]);
            }
        }

        $this->addOnItem = !empty($data['addOnItem']['is_add_on_item']);
        if (!empty($data['availability']['raw'])) {
            $this->availability = $data['availability']['raw'];
        } else {
            $this->availability = null;
        }

        if (!empty($data['prices'][0]['currency'])) {
            $this->priceCurrency = $data['prices'][0]['currency'];
        }
        if (!empty($data['prices'][0]['value'])) {
            $this->priceAmount = $data['prices'][0]['value'];
        }
    }

    public function setPosition($value) {
        $this->position = $value;
    }
    public function setTitle($value) {
        $this->title = $value;
    }
    public function setAsin($value) {
        $this->asin = $value;
    }
    public function setLink($value) {
        $this->link = $value;
    }
    public function setImage($value) {
        $this->image = $value;
    }
    public function setSponsored($value) {
        $this->sponsored = $value;
    }
    public function setCategories($value) {
        $this->categories = $value;
    }
    public function setIsPrime($value) {
        $this->isPrime = $value;
    }
    public function setRatingsTotal($value) {
        $this->ratingsTotal = $value;
    }
    public function setReviewsTotal($value) {
        $this->reviewsTotal = $value;
    }

    public function setRating50FromRating5($rating5) {
        $this->rating50 = 10 * $rating5;
    }
}
