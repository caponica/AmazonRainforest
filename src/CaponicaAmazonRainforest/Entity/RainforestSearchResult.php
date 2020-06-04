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

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Get variant
     *
     * @return string
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Get subTitleText
     *
     * @return string
     */
    public function getSubTitleText()
    {
        return $this->subTitleText;
    }

    /**
     * Get subTitleLink
     *
     * @return string
     */
    public function getSubTitleLink()
    {
        return $this->subTitleLink;
    }

    /**
     * Get isPrime
     *
     * @return boolean
     */
    public function getIsPrime()
    {
        return $this->isPrime;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get sponsored
     *
     * @return boolean
     */
    public function getSponsored()
    {
        return $this->sponsored;
    }

    /**
     * Set addOnItem
     *
     * @param boolean $addOnItem
     *
     * @return RainforestSearchResult
     */
    public function setAddOnItem($addOnItem)
    {
        $this->addOnItem = $addOnItem;

        return $this;
    }

    /**
     * Get addOnItem
     *
     * @return boolean
     */
    public function getAddOnItem()
    {
        return $this->addOnItem;
    }

    /**
     * Get categories
     *
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set availability
     *
     * @param string $availability
     *
     * @return RainforestSearchResult
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability
     *
     * @return string
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set rating50
     *
     * @param integer $rating50
     *
     * @return RainforestSearchResult
     */
    public function setRating50($rating50)
    {
        $this->rating50 = $rating50;

        return $this;
    }

    /**
     * Get rating50
     *
     * @return integer
     */
    public function getRating50()
    {
        return $this->rating50;
    }

    /**
     * Get ratingsTotal
     *
     * @return integer
     */
    public function getRatingsTotal()
    {
        return $this->ratingsTotal;
    }

    /**
     * Get reviewsTotal
     *
     * @return integer
     */
    public function getReviewsTotal()
    {
        return $this->reviewsTotal;
    }

    /**
     * Set priceCurrency
     *
     * @param string $priceCurrency
     *
     * @return RainforestSearchResult
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    /**
     * Get priceCurrency
     *
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * Set priceAmount
     *
     * @param string $priceAmount
     *
     * @return RainforestSearchResult
     */
    public function setPriceAmount($priceAmount)
    {
        $this->priceAmount = $priceAmount;

        return $this;
    }

    /**
     * Get priceAmount
     *
     * @return string
     */
    public function getPriceAmount()
    {
        return $this->priceAmount;
    }

    /**
     * Get priceLowerAmount
     *
     * @return string
     */
    public function getPriceLowerAmount()
    {
        return $this->priceLowerAmount;
    }

    /**
     * Get priceUpperAmount
     *
     * @return string
     */
    public function getPriceUpperAmount()
    {
        return $this->priceUpperAmount;
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
