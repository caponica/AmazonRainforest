<?php

namespace CaponicaAmazonRainforest\Entity;

/**
 * A child object which lives under RainforestSearch or RainforestBestSellers. Represents a single "hit" in the Search results.
 * Note that some fields are only available from Search, some only from BestSellers
 *
 * @see  https://rainforestapi.com/docs/product-data-api/results/search
 * @see  https://rainforestapi.com/docs/product-data-api/results/bestsellers
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
    protected $image;
    protected $rating50;
    protected $ratingsTotal;
    protected $priceCurrency;
    protected $priceAmount;

    // Fields only available under RainforestSearch
    protected $isPrime;
    protected $sponsored;
    protected $addOnItem;
    protected $categories;
    protected $availability;
    protected $reviewsTotal;

    // Fields only available under RainforestBestSellers
    protected $rank;
    protected $subTitleText;
    protected $subTitleLink;
    protected $variant;
    protected $priceLowerAmount;
    protected $priceUpperAmount;

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
            'ratings_total' => 'setRatingsTotal',
            'rating'        => 'setRating50FromRating5',

            'is_prime'      => 'setIsPrime',
            'sponsored'     => 'setSponsored',
            'categories'    => 'setCategories',
            'reviews_total' => 'setReviewsTotal',

            'rank'          => 'setRank',
            'variant'       => 'setVariant',
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

        if (!empty($data['sub_title']['text'])) {
            $this->subTitleText = $data['sub_title']['text'];
        } else {
            $this->subTitleText = null;
        }
        if (!empty($data['sub_title']['link'])) {
            $this->subTitleLink = $data['sub_title']['link'];
        } else {
            $this->subTitleLink = null;
        }

        if (!empty($data['prices'][0]['currency'])) {
            $this->priceCurrency = $data['prices'][0]['currency'];
        }
        if (!empty($data['prices'][0]['value'])) {
            $this->priceAmount = $data['prices'][0]['value'];
        }

        if (!empty($data['price']['currency'])) {
            $this->priceCurrency = $data['price']['currency'];
        }
        if (!empty($data['price']['value'])) {
            $this->priceAmount = $data['price']['value'];
        }
        if (!empty($data['price_lower']['value'])) {
            $this->priceLowerAmount = $data['price_lower']['value'];
            if (empty($this->priceCurrency) && !empty($data['price_lower']['currency'])) {
                $this->priceCurrency = $data['price_lower']['currency'];
            }
        }
        if (!empty($data['price_upper']['value'])) {
            $this->priceUpperAmount = $data['price_upper']['value'];
            if (empty($this->priceCurrency) && !empty($data['price_upper']['currency'])) {
                $this->priceCurrency = $data['price_upper']['currency'];
            }
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

    public function setRank($value) {
        $this->rank = $value;
    }
    public function setSubTitleText($value) {
        $this->subTitleText = $value;
    }
    public function setSubTitleLink($value) {
        $this->subTitleLink = $value;
    }
    public function setVariant($value) {
        $this->variant = $value;
    }
    public function setPriceLowerAmount($value) {
        $this->priceLowerAmount = $value;
    }
    public function setPriceUpperAmount($value) {
        $this->priceUpperAmount = $value;
    }
}
