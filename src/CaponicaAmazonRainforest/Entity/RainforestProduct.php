<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\CommonResponse;
use CaponicaAmazonRainforest\Response\ProductResponse;

/**
 * Converts a ProductResponse into an object representing a product. Main fields have accessors, if you need something
 * that is not available through a local accessor method then you can call getRainforestResponse()->getXyz() to access
 * all data in the underlying response arrays.
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestProduct extends RainforestEntityCommon
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Entity\\RainforestProduct';

    /**
     * @param CommonResponse $rfResponse    A ProductResponse (only declared as 'CommonResponse' for inheritance purposes)
     */
    public function updateFromRainforestResponse(CommonResponse $rfResponse) {
        /** @var ProductResponse $rfResponse */
        $this->setRainforestResponse($rfResponse);
        $this->setAsin($rfResponse->getAsin());
        $this->setParentAsin($rfResponse->getParentAsin());
        $this->setMarketplace($rfResponse->getMarketplaceSuffix());
        $this->setTitle($rfResponse->getTitle());
        $this->setDescription($rfResponse->getDescription());
        $this->setBullets($rfResponse->getBullets());
        $this->setSalesRankFlat($rfResponse->getSalesRankFlat());
        $this->setKeywords($rfResponse->getKeywords());
        $this->setFirstAvailable($rfResponse->getFirstAvailableDate());
        $this->setMainImageLink($rfResponse->getMainImageLink());
        $this->setImageCount($rfResponse->getImageCount());
        $this->setVideoCount($rfResponse->getVideoCount());
        $this->setSalesRank($rfResponse->getSalesRank());
        $this->setSalesRankTlc($rfResponse->getSalesRankTlc());
        $this->setHasCoupon($rfResponse->getHasCoupon());
        $this->setRating50($rfResponse->getRating50());
        $this->setRatingsTotal($rfResponse->getRatingsTotal());
        $this->setReviewsTotal($rfResponse->getReviewsTotal());
        $this->setHasAPlusContent($rfResponse->getHasAPlusContent());
        $this->setIsAPlusThirdParty($rfResponse->getIsAPlusThirdParty());
        $this->setAPlusCompany($rfResponse->getAPlusCompany());
        $this->setModelNumber($rfResponse->getModelNumber());
        $this->setBbIsPrime($rfResponse->getBbIsPrime());
        $this->setBbIsConditionNew($rfResponse->getBbIsConditionNew());
        $this->setBbAvailabilityType($rfResponse->getBbAvailabilityType());
        $this->setBbDispatchDays($rfResponse->getBbDispatchDays());
        $this->setBbAvailabilityRaw($rfResponse->getBbAvailabilityRaw());
        $this->setBbFulType($rfResponse->getBbFulType());
        $this->setBbFulSellerName($rfResponse->getBbFulSellerName());
        $this->setBbPriceCurrency($rfResponse->getBbPriceCurrency());
        $this->setBbPriceAmountFromDecimal($rfResponse->getBbPriceAmount());

        // ProductResponse methods that can throw Exceptions:
        try {
            $this->setWeightPounds($rfResponse->getWeightPounds());
        } catch (\Exception $e) {
            $this->setWeightPounds(null);
        }
        try {
            $this->setWeightShippingPounds($rfResponse->getWeightShippingPounds());
        } catch (\Exception $e) {
            $this->setWeightShippingPounds(null);
        }
        try {
            $this->setDimensionsInches($rfResponse->getDimensionsInchesString());
        } catch (\Exception $e) {
            $this->setDimensionsInches(null);
        }
        try {
            $this->setVolumeCuFt($rfResponse->getVolumeCuFt());
        } catch (\Exception $e) {
            $this->setVolumeCuFt(null);
        }
    }

    public function setBbPriceAmountFromDecimal(string|float $rawValue): static
    {
        return $this->setBbPriceAmount($rawValue);
    }

    protected string $marketplace;

    protected string $asin;
    protected string $parentAsin;

    protected ?int $salesRank = null;
    protected ?string $salesRankTlc = null;
    protected ?string $salesRankFlat = null;

    protected ?\DateTime $firstAvailable = null;
    protected ?string $mainImageLink = null;
    protected int $imageCount = 0;
    protected int $videoCount = 0;

    protected ?int $rating50 = null;
    protected ?int $ratingsTotal = null;
    protected ?int $reviewsTotal = null;

    protected bool $hasAPlusContent = false;
    protected bool $isAPlusThirdParty = false;
    protected ?string $aPlusCompany = null;

    protected bool $hasCoupon = false;
    protected bool $bbIsPrime = false;
    protected bool $bbIsConditionNew = true;
    protected ?string $bbAvailabilityType = null;
    protected ?int $bbDispatchDays = null;
    protected ?string $bbAvailabilityRaw = null;
    protected ?string $bbFulType = null;
    protected ?string $bbFulSellerName = null;
    protected string $bbPriceCurrency;
    protected mixed $bbPriceAmount = null;

    protected ?string $modelNumber = null;
    protected array $bullets = [];
    protected string $title = '';
    protected string $keywords = '';
    protected ?string $description = null;

    protected int|float|null $weightPounds = null;
    protected int|float|null $weightShippingPounds = null;
    protected ?string $dimensionsInches = null;
    protected int|float|null $volumeCuFt = null;

    public function getMarketplace(): ?string
    {
        return $this->marketplace;
    }

    public function setMarketplace(string $marketplace): static
    {
        $this->marketplace = $marketplace;

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

    public function getParentAsin(): ?string
    {
        return $this->parentAsin;
    }

    public function setParentAsin(string $parentAsin): static
    {
        $this->parentAsin = $parentAsin;

        return $this;
    }

    public function getSalesRank(): ?int
    {
        return $this->salesRank;
    }

    public function setSalesRank(?int $salesRank): static
    {
        $this->salesRank = $salesRank;

        return $this;
    }

    public function getSalesRankTlc(): ?string
    {
        return $this->salesRankTlc;
    }

    public function setSalesRankTlc(?string $salesRankTlc): static
    {
        $this->salesRankTlc = $salesRankTlc;

        return $this;
    }

    public function getSalesRankFlat(): ?string
    {
        return $this->salesRankFlat;
    }

    public function setSalesRankFlat(?string $salesRankFlat): static
    {
        $this->salesRankFlat = $salesRankFlat;

        return $this;
    }

    public function getFirstAvailable(): ?\DateTimeInterface
    {
        return $this->firstAvailable;
    }

    public function setFirstAvailable(?\DateTimeInterface $firstAvailable): static
    {
        $this->firstAvailable = $firstAvailable;

        return $this;
    }

    public function getMainImageLink(): ?string
    {
        return $this->mainImageLink;
    }

    public function setMainImageLink(?string $mainImageLink): static
    {
        $this->mainImageLink = $mainImageLink;

        return $this;
    }

    public function getImageCount(): ?int
    {
        return $this->imageCount;
    }

    public function setImageCount(int $imageCount): static
    {
        $this->imageCount = $imageCount;

        return $this;
    }

    public function getVideoCount(): ?int
    {
        return $this->videoCount;
    }

    public function setVideoCount(int $videoCount): static
    {
        $this->videoCount = $videoCount;

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

    public function setRatingsTotal(?int $ratingsTotal): static
    {
        $this->ratingsTotal = $ratingsTotal;

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

    public function getHasAPlusContent(): ?bool
    {
        return $this->hasAPlusContent;
    }

    public function setHasAPlusContent(bool $hasAPlusContent): static
    {
        $this->hasAPlusContent = $hasAPlusContent;

        return $this;
    }

    public function getIsAPlusThirdParty(): ?bool
    {
        return $this->isAPlusThirdParty;
    }

    public function setIsAPlusThirdParty(bool $isAPlusThirdParty): static
    {
        $this->isAPlusThirdParty = $isAPlusThirdParty;

        return $this;
    }

    public function getAPlusCompany(): ?string
    {
        return $this->aPlusCompany;
    }

    public function setAPlusCompany(?string $aPlusCompany): static
    {
        $this->aPlusCompany = $aPlusCompany;

        return $this;
    }

    public function getWeightPounds(): int|float|null
    {
        return $this->weightPounds;
    }

    public function setWeightPounds(int|float|null $weightPounds): static
    {
        $this->weightPounds = $weightPounds;

        return $this;
    }

    public function getWeightShippingPounds(): int|float|null
    {
        return $this->weightShippingPounds;
    }

    public function setWeightShippingPounds(int|float|null $weightShippingPounds): static
    {
        $this->weightShippingPounds = $weightShippingPounds;

        return $this;
    }

    public function getDimensionsInches(): ?string
    {
        return $this->dimensionsInches;
    }

    public function setDimensionsInches(?string $dimensionsInches): static
    {
        $this->dimensionsInches = $dimensionsInches;

        return $this;
    }

    public function getVolumeCuFt(): int|float|null
    {
        return $this->volumeCuFt;
    }

    public function setVolumeCuFt(int|float|null $volumeCuFt): static
    {
        $this->volumeCuFt = $volumeCuFt;

        return $this;
    }

    public function getHasCoupon(): ?bool
    {
        return $this->hasCoupon;
    }

    public function setHasCoupon(bool $hasCoupon): static
    {
        $this->hasCoupon = $hasCoupon;

        return $this;
    }

    public function getBbIsPrime(): ?bool
    {
        return $this->bbIsPrime;
    }

    public function setBbIsPrime(bool $bbIsPrime): static
    {
        $this->bbIsPrime = $bbIsPrime;

        return $this;
    }

    public function getBbIsConditionNew(): ?bool
    {
        return $this->bbIsConditionNew;
    }

    public function setBbIsConditionNew(bool $bbIsConditionNew): static
    {
        $this->bbIsConditionNew = $bbIsConditionNew;

        return $this;
    }

    public function getBbAvailabilityType(): ?string
    {
        return $this->bbAvailabilityType;
    }

    public function setBbAvailabilityType(?string $bbAvailabilityType): static
    {
        $this->bbAvailabilityType = $bbAvailabilityType;

        return $this;
    }

    public function getBbDispatchDays(): ?int
    {
        return $this->bbDispatchDays;
    }

    public function setBbDispatchDays(?int $bbDispatchDays): static
    {
        $this->bbDispatchDays = $bbDispatchDays;

        return $this;
    }

    public function getBbAvailabilityRaw(): ?string
    {
        return $this->bbAvailabilityRaw;
    }

    public function setBbAvailabilityRaw(?string $bbAvailabilityRaw): static
    {
        $this->bbAvailabilityRaw = $bbAvailabilityRaw;

        return $this;
    }

    public function getBbFulType(): ?string
    {
        return $this->bbFulType;
    }

    public function setBbFulType(?string $bbFulType): static
    {
        $this->bbFulType = $bbFulType;

        return $this;
    }

    public function getBbFulSellerName(): ?string
    {
        return $this->bbFulSellerName;
    }

    public function setBbFulSellerName(?string $bbFulSellerName): static
    {
        $this->bbFulSellerName = $bbFulSellerName;

        return $this;
    }

    public function getBbPriceCurrency(): ?string
    {
        return $this->bbPriceCurrency;
    }

    public function setBbPriceCurrency(?string $bbPriceCurrency): static
    {
        $this->bbPriceCurrency = $bbPriceCurrency;

        return $this;
    }

    public function getBbPriceAmount(): int|float|null
    {
        return $this->bbPriceAmount;
    }

    public function setBbPriceAmount(int|float|string|null $bbPriceAmount): static
    {
        $this->bbPriceAmount = $bbPriceAmount * 1;

        return $this;
    }

    public function getModelNumber(): ?string
    {
        return $this->modelNumber;
    }

    public function setModelNumber(?string $modelNumber): static
    {
        $this->modelNumber = $modelNumber;

        return $this;
    }

    public function getBullets(): array
    {
        return $this->bullets;
    }

    public function setBullets(array $bullets): static
    {
        $this->bullets = $bullets;

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

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
