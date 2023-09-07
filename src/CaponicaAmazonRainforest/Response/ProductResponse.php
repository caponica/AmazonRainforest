<?php

namespace CaponicaAmazonRainforest\Response;

/**
 * Wrapper for the raw response received back from a request to the Rainforest Product API
 *
 * @package CaponicaAmazonRainforest\Response
 */
class ProductResponse extends CommonResponse
{
    const CLASS_NAME = 'CaponicaAmazonRainforest\\Response\\ProductResponse';

    const MAIN_KEY_PRODUCT              = 'product';

    const MAIN_KEY_ALSO_BOUGHT          = 'also_bought';
    const MAIN_KEY_ALSO_VIEWED          = 'also_viewed';
    const MAIN_KEY_FREQUENTLY_BOUGHT    = 'frequently_bought_together';
    const MAIN_KEY_SPONSORED_PRODUCTS   = 'sponsored_products';
    const MAIN_KEY_TRACK_LIST           = 'track_list';
    const MAIN_KEY_VIEW_TO_PURCHASE     = 'view_to_purchase';

    const WEIGHT_CONVERT_OZ_TO_LB       = 0.0625;
    const WEIGHT_CONVERT_KG_TO_LB       = 2.2046;
    const WEIGHT_CONVERT_G_TO_LB        = 0.0022046;
    const LENGTH_CONVERT_CM_TO_IN       = 0.393701;

    private $product = null;

    private $alsoBought = null;
    private $alsoViewed = null;
    private $fbt = null;
    private $sponsoredProducts = null;
    private $trackList = null;
    private $viewToPurchase = null;

    public function __construct($rfData)
    {
        parent::__construct($rfData);

        // main product data:
        $this->product              = &$this->data[self::MAIN_KEY_PRODUCT];

        // occasional data:
        if (isset($this->data[self::MAIN_KEY_ALSO_BOUGHT])) {
            $this->alsoBought           = &$this->data[self::MAIN_KEY_ALSO_BOUGHT];
        }
        if (isset($this->data[self::MAIN_KEY_ALSO_VIEWED])) {
            $this->alsoViewed           = &$this->data[self::MAIN_KEY_ALSO_VIEWED];
        }
        if (isset($this->data[self::MAIN_KEY_FREQUENTLY_BOUGHT])) {
            $this->fbt                  = &$this->data[self::MAIN_KEY_FREQUENTLY_BOUGHT];
        }
        if (isset($this->data[self::MAIN_KEY_SPONSORED_PRODUCTS])) {
            $this->sponsoredProducts    = &$this->data[self::MAIN_KEY_SPONSORED_PRODUCTS];
        }
        if (isset($this->data[self::MAIN_KEY_TRACK_LIST])) {
            $this->trackList            = &$this->data[self::MAIN_KEY_TRACK_LIST];
        }
        if (isset($this->data[self::MAIN_KEY_VIEW_TO_PURCHASE])) {
            $this->viewToPurchase       = &$this->data[self::MAIN_KEY_VIEW_TO_PURCHASE];
        }
    }

    public static function getMainKeys(): array
    {
        $keys = parent::getMainKeys();
        $keys[] = self::MAIN_KEY_PRODUCT;
        return $keys;
    }
    public static function getOccasionalKeys(): array
    {
        return [
            self::MAIN_KEY_ALSO_BOUGHT,
            self::MAIN_KEY_ALSO_VIEWED,
            self::MAIN_KEY_FREQUENTLY_BOUGHT,
            self::MAIN_KEY_SPONSORED_PRODUCTS,
            self::MAIN_KEY_TRACK_LIST,
            self::MAIN_KEY_VIEW_TO_PURCHASE,
        ];
    }

    public function getProductField($key, $valueIfMissing=null) {
        if (empty($this->product[$key])) {
            return $valueIfMissing;
        }
        return $this->product[$key];
    }
    public function getSpecificationField($key, $valueIfMissing=null) {
        $specs = $this->getProductField('specifications');
        foreach ($specs as $spec) {
            if ($spec['name'] == $key) {
                return $spec['value'];
            }
        }
        return $valueIfMissing;
    }

    public function getAlsoBought() {
        if (empty($this->alsoBought)) {
            return null;
        }
        return $this->alsoBought;
    }
    public function getAlsoViewed() {
        if (empty($this->alsoViewed)) {
            return null;
        }
        return $this->alsoViewed;
    }
    public function getFrequentlyBoughtTogether() {
        if (empty($this->fbt)) {
            return null;
        }
        return $this->fbt;
    }
    public function getSponsoredProducts() {
        if (empty($this->sponsoredProducts)) {
            return null;
        }
        return $this->sponsoredProducts;
    }
    public function getTrackList() {
        if (empty($this->trackList)) {
            return null;
        }
        return $this->trackList;
    }
    public function getViewToPurchase() {
        if (empty($this->viewToPurchase)) {
            return null;
        }
        return $this->viewToPurchase;
    }

    public function getFirstAvailableDate(): ?\DateTime
    {
        $firstArray = $this->getProductField('first_available');
        if (empty($firstArray) || empty($firstArray['utc'])) {
            return null;
        }
        try {
            return new \DateTime($firstArray['utc']);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $weightString
     * @return float|int|null
     * @throws \Exception
     */
    private function convertWeightStringToPounds($weightString): float|int|null
    {
        if (empty($weightString)) {
            return null;
        }

        $weightString = strtolower($weightString);

        $regexWeight = preg_match('/^[^0-9.]*([0-9.]+) ([^ ]+)$/i', $weightString, $matches);
        if (!$regexWeight) {
            throw new \Exception("Could not parse weight string: $weightString");
        }
        $weightNumber = (float) $matches[1];
        if (empty($weightNumber)) {
            return null;
        }

        if ('ounces' === $matches[2]) {
            return $weightNumber * self::WEIGHT_CONVERT_OZ_TO_LB;
        } elseif ('pounds' === $matches[2] || ('lb' === $matches[2])) {
            return $weightNumber;
        } elseif ('grams' === $matches[2] || 'g' === $matches[2]) {
            return $weightNumber * self::WEIGHT_CONVERT_G_TO_LB;
        } elseif ('kilograms' === $matches[2] || 'kg' === $matches[2]) {
            return $weightNumber * self::WEIGHT_CONVERT_KG_TO_LB;
        }
        throw new \Exception("Unknown units in weight string: $weightString");
    }
    /**
     * @return float|int|null
     * @throws \Exception
     */
    public function getWeightPounds(): float|int|null
    {
        $weight = $this->getProductField('weight');
        if (empty($weight)) {
            $weight = $this->extractWeightStringFromDimensions();
        }
        if (empty($weight)) {
            return null;
        }
        return $this->convertWeightStringToPounds($weight);
    }
    /**
     * @return float|int|null
     * @throws \Exception
     */
    public function getWeightShippingPounds(): float|int|null
    {
        return $this->convertWeightStringToPounds($this->getProductField('shipping_weight'));
    }

    protected function extractWeightStringFromDimensions(): ?string
    {
        $dimensionsString = $this->getDimensionsString();
        if (empty($dimensionsString)) {
            return null;
        }
        $matches = [];
        // 1.18 x 4.02 x 5 inches; 2.4 Ounces
        if (preg_match('/; ([0-9.]+ [a-z]+)$/i', $dimensionsString, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    public function getDimensionsInchesArray(): ?array
    {
        $dimensionsString = $this->getDimensionsString();
        if (empty($dimensionsString)) {
            return null;
        }
        $matches = [];
        if (preg_match('/^([0-9.]+)[ x]+([0-9.]+)[ x]+([0-9.]+) ([a-z]+)/i', $dimensionsString, $matches)) {
            $dims = [
                1*$matches[1],
                1*$matches[2],
                1*$matches[3],
            ];
            $units = $matches[4];
        } else {
            throw new \Exception("Could not parse dimension string $dimensionsString");
        }

        if ('cm' === $units) {
            foreach ($dims as &$dim) {
                $dim = round($dim * self::LENGTH_CONVERT_CM_TO_IN, 2);
            }
        } elseif ('inches' === $units) {
            // Do nothing
        } else {
            throw new \Exception("Could not parse units in dimension string $dimensionsString");
        }

        sort($dims);
        return $dims;
    }
    public function getDimensionsString(): ?string
    {
        $dimensionsString = $this->getProductField('dimensions');
        if (!is_null($dimensionsString)) {
            $dimensionsString = trim(strtolower($dimensionsString));
        }
        if (!empty($dimensionsString)) {
            return $dimensionsString;
        }
        $dimensionsString = $this->getSpecificationField('Package Dimensions');
        if (!empty($dimensionsString)) {
            return $dimensionsString;
        }
        return null;
    }

    /**
     * @return string|null
     * @throws \Exception
     */
    public function getDimensionsInchesString(): ?string
    {
        $dims = $this->getDimensionsInchesArray();
        if (empty($dims)) {
            return null;
        }

        return implode('x', $dims);
    }
    /**
     * @return float|null
     * @throws \Exception
     */
    public function getVolumeCuFt(): float|null {
        $dims = $this->getDimensionsInchesArray();
        if (empty($dims)) {
            return null;
        }

        $volume = 1;
        foreach ($dims as $dim) {
            $volume *= $dim;
        }

        return $volume / 1728; // convert cubic inches to cuft
    }

    public function getRating50(): ?int {
        return 10 * $this->getProductField('rating');
    }

    public function getMainImageLink() {
        $mainImage = $this->getProductField('main_image');
        if (empty($mainImage) || empty($mainImage['link'])) {
            return null;
        }
        return $mainImage['link'];
    }
    public function getBestsellersRankFlat() { // alias for getSalesRankFlat
        return $this->getSalesRankFlat();
    }
    public function getSalesRankFlat() {
        return $this->getProductField('bestsellers_rank_flat');
    }
    public function getSalesRank() {
        $rankArray = $this->getProductField('bestsellers_rank');
        if (empty($rankArray)) {
            return null;
        }

        // Make a guess at the top level category
        $tlc = null;
        foreach ($rankArray as $rankData) {
            if (empty($tlc) || ($tlc['rank'] < $rankData['rank'])) {
                $tlc = $rankData;
            }
        }

        return $tlc['rank'];
    }
    public function getSalesRankTlc() {
        $rankArray = $this->getProductField('bestsellers_rank');
        if (empty($rankArray)) {
            return null;
        }

        // Make a guess at the top level category
        $tlc = null;
        foreach ($rankArray as $rankData) {
            if (empty($tlc) || ($tlc['rank'] < $rankData['rank'])) {
                $tlc = $rankData;
            }
        }

        return $tlc['category'];
    }

    public function getHasAPlusContent(): bool
    {
        $aPlusArray = $this->getProductField('a_plus_content');
        if (empty($aPlusArray)) {
            return false;
        }
        if (!empty($aPlusArray['has_a_plus_content'])) {
            return true;
        }
        return false;
    }
    public function getIsAPlusThirdParty(): bool
    {
        $aPlusArray = $this->getProductField('a_plus_content');
        if (empty($aPlusArray)) {
            return false;
        }
        if (!empty($aPlusArray['third_party'])) {
            return true;
        }
        return false;
    }
    public function getAPlusCompany() {
        $aPlusArray = $this->getProductField('a_plus_content');
        if (empty($aPlusArray)) {
            return null;
        }
        if (!empty($aPlusArray['company_description_text'])) {
            return $aPlusArray['company_description_text'];
        }
        return null;
    }

    public function getBbIsPrime(): bool
    {
        $bbArray = $this->getProductField('buybox_winner');
        if (empty($bbArray)) {
            return false;
        }
        if (!empty($bbArray['is_prime'])) {
            return true;
        }
        return false;
    }
    public function getBbIsConditionNew(): bool
    {
        $bbArray = $this->getProductField('buybox_winner');
        if (empty($bbArray)) {
            return false;
        }
        if (empty($bbArray['condition'])) {
            return false;
        }
        if (!empty($bbArray['condition']['is_new'])) {
            return true;
        }
        return false;
    }
    private function getBbAvailabilityField($key) {
        $bbArray = $this->getProductField('buybox_winner');
        if (empty($bbArray)) {
            return null;
        }

        if (empty($bbArray['availability'])) {
            return null;
        }

        if (empty($bbArray['availability'][$key])) {
            return null;
        }
        return $bbArray['availability'][$key];
    }
    public function getBbAvailabilityType() {
        return $this->getBbAvailabilityField('type');
    }
    public function getBbAvailabilityStock() {
        return $this->getBbAvailabilityField('stock_level');
    }
    public function getBbDispatchDays() {
        return $this->getBbAvailabilityField('dispatch_days');
    }
    public function getBbAvailabilityRaw() {
        return $this->getBbAvailabilityField('raw');
    }
    public function getBbFulType() {
        $bbArray = $this->getProductField('buybox_winner');
        if (empty($bbArray) || empty($bbArray['fulfillment'])) {
            return null;
        }

        if (empty($bbArray['fulfillment']['type'])) {
            return null;
        }

        return $bbArray['fulfillment']['type'];
    }
    public function getBbFulSellerName() {
        $bbArray = $this->getProductField('buybox_winner');
        if (empty($bbArray) || empty($bbArray['fulfillment'])) {
            return null;
        }

        if (empty($bbArray['fulfillment']['third_party_seller'])) {
            return null;
        }

        if (!empty($bbArray['fulfillment']['third_party_seller']['name'])) {
            return $bbArray['fulfillment']['third_party_seller']['name'];
        }

        return null;
    }
    public function getBbPriceCurrency() {
        $bbArray = $this->getProductField('buybox_winner');
        if (empty($bbArray) || empty($bbArray['price'])) {
            return null;
        }

        if (!empty($bbArray['price']['currency'])) {
            return $bbArray['price']['currency'];
        }

        return null;
    }
    public function getBbPriceAmount() {
        $bbArray = $this->getProductField('buybox_winner');
        if (empty($bbArray) || empty($bbArray['price'])) {
            return null;
        }

        if (!empty($bbArray['price']['value'])) {
            return $bbArray['price']['value'];
        }

        return null;
    }

    public function getAsin() {
        return $this->getProductField('asin');
    }
    public function getParentAsin() {
        return $this->getProductField('parent_asin');
    }
    public function getTitle() {
        return $this->getProductField('title');
    }
    public function getBullets() {
        return $this->getProductField('feature_bullets');
    }
    public function getDescription() {
        return $this->getProductField('description');
    }
    public function getKeywords() {
        return $this->getProductField('keywords');
    }
    public function getModelNumber() {
        return $this->getProductField('model_number');
    }
    public function getRecommendedAge() {
        return $this->getProductField('recommended_age');
    }
    public function getImageCount() {
        return $this->getProductField('images_count', 0);
    }
    public function getVideoCount() {
        return $this->getProductField('videos_count', 0);
    }
    public function getHasCoupon() {
        return $this->getProductField('has_coupon', false);
    }
    public function getRatingsTotal() {
        return $this->getProductField('ratings_total');
    }
    public function getReviewsTotal() {
        return $this->getProductField('reviews_total');
    }
    public function getLanguage() {
        return $this->getProductField('language');
    }
}