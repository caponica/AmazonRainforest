<?php

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Response\ReviewResponse;

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

    /**
     * The original data array used to build this object - in case you need something not cleanly converted
     *
     * @var array $data
     */
    protected array $data;

    protected int $position;
    protected string $title;
    protected ?string $brand = null;
    protected string $asin;
    protected string $link;
    protected string $image;
    protected ?int $rating50 = null;
    protected ?int $ratingsTotal = null;
    protected ?string $priceCurrency = null;
    protected mixed $priceAmount = null;
    protected bool $featuredFromOurBrands = false;
    protected ?string $information = null;
    protected ?string $recentSales = null;
    protected ?string $recentViews = null;
    protected bool $isCarousel = false;

    protected bool $isAmazonBrand = false;
    protected bool $isAmazonExclusive = false;
    protected bool $isAmazonFresh = false;
    protected bool $isSmallBusiness = false;
    protected ?string $couponBadgeText = null;
    protected ?string $couponExtraText = null;
    protected ?string $amazonChoiceKeywords = null;
    protected ?string $bestSellerLink = null;
    protected ?string $bestSellerCategory = null;
    protected ?string $unitPrice = null;

    // Fields only available under RainforestSearch
    protected bool $isPrime = false;
    protected bool $sponsored = false;
    protected bool $addOnItem = false;
    protected mixed $categories = null;
    protected ?string $availability = null;
    protected ?int $reviewsTotal = null;

    // Fields only available under RainforestBestSellers
    protected ?int $rank = null;
    protected ?string $subTitleText = null;
    protected ?string $subTitleLink = null;
    protected ?string $variant = null;
    protected mixed $priceLowerAmount = null;
    protected mixed $priceUpperAmount = null;

    // Deal object fields
    protected ?string $dealLink = null;
    protected ?string $dealText = null;
    protected ?string $dealBadgeText = null;

    // Climate Pledge Friendly object fields
    protected ?string $climatePledgeLink = null;
    protected ?string $climatePledgeText = null;
    protected ?string $climatePledgeImage = null;

    // Gift Guide object fields
    protected ?string $giftGuideLink = null;
    protected ?string $giftGuideBadgeText = null;

    // Carousel object fields
    protected ?string $carouselTitle = null;
    protected ?string $carouselSubTitle = null;
    protected ?bool $carouselSponsored = null;
    protected ?string $carouselId = null;
    protected ?int $carouselTotalItems = null;

    /**
     * @param array $data       Raw array of data from the API
     */
    public function updateFromSearchResultArray($data) {
        $this->setData($data);
        $fields = [
            'position'              => 'setPosition',
            'title'                 => 'setTitle',
            'asin'                  => 'setAsin',
            'link'                  => 'setLink',
            'image'                 => 'setImage',
            'ratings_total'         => 'setRatingsTotal',
            'rating'                => 'setRating50FromRating5',

            'is_amazon_brand'       => 'setIsAmazonBrand',
            'is_exclusive_to_amazon'=> 'setIsAmazonExclusive',
            'is_amazon_fresh'       => 'setIsAmazonFresh',
            'is_small_business'     => 'setIsSmallBusiness',
            'unit_price'            => 'setUnitPrice',
            'bestseller'            => 'setBestSellerDetails',
            'coupon'                => 'setCouponDetails',
            'amazons_choice'        => 'setAmazonChoiceKeywordsFromArray',

            'is_prime'              => 'setIsPrime',
            'sponsored'             => 'setSponsored',
            'categories'            => 'setCategories',
            'reviews_total'         => 'setReviewsTotal',

            'rank'                  => 'setRank',
            'variant'               => 'setVariant',

            // New simple fields
            'brand'                     => 'setBrand',
            'featured_from_our_brands'  => 'setFeaturedFromOurBrands',
            'information'               => 'setInformation',
            'recent_sales'              => 'setRecentSales',
            'recent_views'              => 'setRecentViews',
            'is_carousel'               => 'setIsCarousel',

            // Complex object fields
            'deal'                      => 'setDealDetailsFromArray',
            'climate_pledge_friendly'   => 'setClimatePledgeDetailsFromArray',
            'gift_guide'                => 'setGiftGuideDetailsFromArray',
            'carousel'                  => 'setCarouselDetailsFromArray',
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

        if (!empty($data['price']['value'])) {
            $this->setPriceDetailsFromArray($data['price']);
        } elseif (!empty($data['prices'][0]['value'])) {
            $this->setPriceDetailsFromArray($data['prices'][0]);
        }

        if (!empty($data['price_lower']['value'])) {
            $this->setPriceLowerDetailsFromArray($data['price_lower']);
        }
        if (!empty($data['price_upper']['value'])) {
            $this->setPriceUpperDetailsFromArray($data['price_upper']);
        }
    }

    protected function setData($value): static
    {
        $this->data = $value;
        return $this;
    }
    public function getOriginalDataArray(): array
    {
        return $this->data;
    }

    public function setRating50FromRating5($rating5): void
    {
        if (empty($rating5)) {
            $this->setRating50(null);
            return;
        }
        $this->setRating50(10 * $rating5);
    }

    public function setBestSellerDetails(array $dataArray): void
    {
        $this->bestSellerLink = $dataArray['link'] ?? null;
        $this->bestSellerCategory = $dataArray['category'] ?? null;
    }

    public function setCouponDetails($couponArray): void
    {
        $this->couponBadgeText = $couponArray['badge_text'] ?? null;
        $this->couponExtraText = $couponArray['text'] ?? null;
    }

    public function setAmazonChoiceKeywordsFromArray($choiceArray): void
    {
        $this->amazonChoiceKeywords = $choiceArray['keywords'] ?? null;
    }

    public function setDealDetailsFromArray($dealArray): void
    {
        $this->dealLink = $dealArray['link'] ?? null;
        $this->dealText = $dealArray['text'] ?? null;
        $this->dealBadgeText = $dealArray['badge_text'] ?? null;
    }

    public function setClimatePledgeDetailsFromArray($climateArray): void
    {
        $this->climatePledgeLink = $climateArray['link'] ?? null;
        $this->climatePledgeText = $climateArray['text'] ?? null;
        $this->climatePledgeImage = $climateArray['image'] ?? null;
    }

    public function setGiftGuideDetailsFromArray($giftArray): void
    {
        $this->giftGuideLink = $giftArray['link'] ?? null;
        $this->giftGuideBadgeText = $giftArray['badge_text'] ?? null;
    }

    public function setCarouselDetailsFromArray($carouselArray): void
    {
        $this->carouselTitle = $carouselArray['title'] ?? null;
        $this->carouselSubTitle = $carouselArray['sub_title'] ?? null;
        $this->carouselSponsored = $carouselArray['sponsored'] ?? null;
        $this->carouselId = $carouselArray['id'] ?? null;
        $this->carouselTotalItems = $carouselArray['total_items'] ?? null;
    }

    public function setPriceDetailsFromArray($priceArray): void
    {
        if (!empty($priceArray['currency'])) {
            $this->setPriceCurrency($priceArray['currency']);
        }
        if (!empty($priceArray['value'])) {
            $this->setPriceAmountFromDecimal($priceArray['value']);
        }
    }
    public function setPriceLowerDetailsFromArray($priceArray): void
    {
        if (!empty($priceArray['currency']) && empty($this->getPriceCurrency())) {
            $this->setPriceCurrency($priceArray['currency']);
        }
        if (!empty($priceArray['value'])) {
            $this->setPriceLowerAmountFromDecimal($priceArray['value']);
        }
    }
    public function setPriceUpperDetailsFromArray($priceArray): void
    {
        if (!empty($priceArray['currency']) && empty($this->getPriceCurrency())) {
            $this->setPriceCurrency($priceArray['currency']);
        }
        if (!empty($priceArray['value'])) {
            $this->setPriceUpperAmountFromDecimal($priceArray['value']);
        }
    }

    public function setPriceAmountFromDecimal(string|float $rawValue): static
    {
        return $this->setPriceAmount($rawValue);
    }
    public function setPriceLowerAmountFromDecimal(string|float $rawValue): static
    {
        return $this->setPriceLowerAmount($rawValue);
    }
    public function setPriceUpperAmountFromDecimal(string|float $rawValue): static
    {
        return $this->setPriceUpperAmount($rawValue);
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAsin(): ?string
    {
        return $this->asin;
    }

    public function setAsin(string $asin): static
    {
        $this->asin = $asin;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getRating50(): ?int
    {
        return $this->rating50;
    }

    public function setRating50(?int $rating50): static
    {
        $this->rating50 = $rating50;

        return $this;
    }

    public function getRatingsTotal(): ?int
    {
        return $this->ratingsTotal;
    }

    public function setRatingsTotal(int|string $ratingsTotal): static
    {
        if (is_string($ratingsTotal)) {
            if (empty($ratingsTotal)) {
                $ratingsTotal = 0;
            } elseif (ctype_digit($ratingsTotal)) {
                $ratingsTotal = (int)$ratingsTotal;
            } else {
                throw new \InvalidArgumentException("RatingsTotal must be an integer or a string of digits");
            }
        }
        $this->ratingsTotal = $ratingsTotal;

        return $this;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->priceCurrency;
    }

    public function setPriceCurrency(?string $priceCurrency): static
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    public function getPriceAmount(): mixed
    {
        return $this->priceAmount;
    }

    public function setPriceAmount(mixed $priceAmount): static
    {
        $this->priceAmount = $priceAmount;

        return $this;
    }

    public function getIsAmazonBrand(): ?bool
    {
        return $this->isAmazonBrand;
    }

    public function setIsAmazonBrand(bool $isAmazonBrand): static
    {
        $this->isAmazonBrand = $isAmazonBrand;

        return $this;
    }

    public function getIsAmazonExclusive(): ?bool
    {
        return $this->isAmazonExclusive;
    }

    public function setIsAmazonExclusive(bool $isAmazonExclusive): static
    {
        $this->isAmazonExclusive = $isAmazonExclusive;

        return $this;
    }

    public function getIsAmazonFresh(): ?bool
    {
        return $this->isAmazonFresh;
    }

    public function setIsAmazonFresh(bool $isAmazonFresh): static
    {
        $this->isAmazonFresh = $isAmazonFresh;

        return $this;
    }

    public function getIsSmallBusiness(): ?bool
    {
        return $this->isSmallBusiness;
    }

    public function setIsSmallBusiness(bool $isSmallBusiness): static
    {
        $this->isSmallBusiness = $isSmallBusiness;

        return $this;
    }

    public function getCouponBadgeText(): ?string
    {
        return $this->couponBadgeText;
    }

    public function setCouponBadgeText(?string $couponBadgeText): static
    {
        $this->couponBadgeText = $couponBadgeText;

        return $this;
    }

    public function getCouponExtraText(): ?string
    {
        return $this->couponExtraText;
    }

    public function setCouponExtraText(?string $couponExtraText): static
    {
        $this->couponExtraText = $couponExtraText;

        return $this;
    }

    public function getAmazonChoiceKeywords(): ?string
    {
        return $this->amazonChoiceKeywords;
    }

    public function setAmazonChoiceKeywords(?string $amazonChoiceKeywords): static
    {
        $this->amazonChoiceKeywords = $amazonChoiceKeywords;

        return $this;
    }

    public function getBestSellerLink(): ?string
    {
        return $this->bestSellerLink;
    }

    public function setBestSellerLink(?string $bestSellerLink): static
    {
        $this->bestSellerLink = $bestSellerLink;

        return $this;
    }

    public function getBestSellerCategory(): ?string
    {
        return $this->bestSellerCategory;
    }

    public function setBestSellerCategory(?string $bestSellerCategory): static
    {
        $this->bestSellerCategory = $bestSellerCategory;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?string $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getIsPrime(): ?bool
    {
        return $this->isPrime;
    }

    public function setIsPrime(bool $isPrime): static
    {
        $this->isPrime = $isPrime;

        return $this;
    }

    public function getSponsored(): ?bool
    {
        return $this->sponsored;
    }

    public function setSponsored(bool $sponsored): static
    {
        $this->sponsored = $sponsored;

        return $this;
    }

    public function getAddOnItem(): ?bool
    {
        return $this->addOnItem;
    }

    public function setAddOnItem(bool $addOnItem): static
    {
        $this->addOnItem = $addOnItem;

        return $this;
    }

    public function getCategories(): ?string
    {
        return $this->categories;
    }

    public function setCategories(mixed $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(?string $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    public function getReviewsTotal(): ?int
    {
        return $this->reviewsTotal;
    }

    public function setReviewsTotal(?int $reviewsTotal): static
    {
        $this->reviewsTotal = $reviewsTotal;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getSubTitleText(): ?string
    {
        return $this->subTitleText;
    }

    public function setSubTitleText(?string $subTitleText): static
    {
        $this->subTitleText = $subTitleText;

        return $this;
    }

    public function getSubTitleLink(): ?string
    {
        return $this->subTitleLink;
    }

    public function setSubTitleLink(?string $subTitleLink): static
    {
        $this->subTitleLink = $subTitleLink;

        return $this;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function setVariant(?string $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function getPriceLowerAmount(): mixed
    {
        return $this->priceLowerAmount;
    }

    public function setPriceLowerAmount(mixed $priceLowerAmount): static
    {
        $this->priceLowerAmount = $priceLowerAmount;

        return $this;
    }

    public function getPriceUpperAmount(): mixed
    {
        return $this->priceUpperAmount;
    }

    public function setPriceUpperAmount(mixed $priceUpperAmount): static
    {
        $this->priceUpperAmount = $priceUpperAmount;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getFeaturedFromOurBrands(): bool
    {
        return $this->featuredFromOurBrands;
    }

    public function setFeaturedFromOurBrands(bool $featuredFromOurBrands): static
    {
        $this->featuredFromOurBrands = $featuredFromOurBrands;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): static
    {
        $this->information = $information;

        return $this;
    }

    public function getRecentSales(): ?string
    {
        return $this->recentSales;
    }

    public function setRecentSales(?string $recentSales): static
    {
        $this->recentSales = $recentSales;

        return $this;
    }

    public function getRecentViews(): ?string
    {
        return $this->recentViews;
    }

    public function setRecentViews(?string $recentViews): static
    {
        $this->recentViews = $recentViews;

        return $this;
    }

    public function getIsCarousel(): bool
    {
        return $this->isCarousel;
    }

    public function setIsCarousel(bool $isCarousel): static
    {
        $this->isCarousel = $isCarousel;

        return $this;
    }

    public function getDealLink(): ?string
    {
        return $this->dealLink;
    }

    public function getDealText(): ?string
    {
        return $this->dealText;
    }

    public function getDealBadgeText(): ?string
    {
        return $this->dealBadgeText;
    }

    public function getClimatePledgeLink(): ?string
    {
        return $this->climatePledgeLink;
    }

    public function getClimatePledgeText(): ?string
    {
        return $this->climatePledgeText;
    }

    public function getClimatePledgeImage(): ?string
    {
        return $this->climatePledgeImage;
    }

    public function getGiftGuideLink(): ?string
    {
        return $this->giftGuideLink;
    }

    public function getGiftGuideBadgeText(): ?string
    {
        return $this->giftGuideBadgeText;
    }

    public function getCarouselTitle(): ?string
    {
        return $this->carouselTitle;
    }

    public function getCarouselSubTitle(): ?string
    {
        return $this->carouselSubTitle;
    }

    public function getCarouselSponsored(): ?bool
    {
        return $this->carouselSponsored;
    }

    public function getCarouselId(): ?string
    {
        return $this->carouselId;
    }

    public function getCarouselTotalItems(): ?int
    {
        return $this->carouselTotalItems;
    }
}
