<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26/05/2020
 * Time: 19:46
 */

namespace CaponicaAmazonRainforest\Entity;

/**
 * A child object which lives under RainforestReviewList. Represents a single "hit" in the ReviewList. You can access the
 * original data array used to build the object via getOriginalDataArray();
 *
 * @see  https://rainforestapi.com/docs/product-data-api/results/reviews
 *
 * @package CaponicaAmazonRainforest\Entity
 */
class RainforestReview
{
    /**
     * @param array|null $data
     */
    public function __construct(?array $data = null) {
        if (!empty($data)) {
            $this->updateFromDataArray($data);
        }
    }

    /**
     * The original data array used to build this object - in case you need something not cleanly converted
     *
     * @var array $data
     */
    protected array $data;

    protected ?string $idString;
    protected ?string $title;
    protected ?string $body;
//    protected $images;
//    protected $videos;
    protected ?string $link;
    protected ?int $rating50;
    protected ?int $helpfulVotes;
    protected ?int $comments;
    protected ?string $reviewCountry;
    protected ?bool $isGlobalReview;
    protected ?bool $verifiedPurchase;
    protected ?bool $vineProgram;
    protected ?bool $vineProgramFree;
    protected ?array $attributes;
    protected ?\DateTime $reviewDate;
    protected ?string $profileName;
    protected ?string $profileLink;
    protected ?string $mfgReplyBody;
    protected ?\DateTime $mfgReplyDate;
    protected ?string $mfgReplyProfileName;
    protected ?string $mfgReplyProfileLink;

    /**
     * @param array $data       Raw array of data from the API
     */
    public function updateFromDataArray(array $data) {
        $this->setData($data);
        $mapping = [
            'id'                        => 'setIdString',
            'title'                     => 'setTitle',
            'body'                      => 'setBody',
            'link'                      => 'setLink',
            'rating'                    => 'setRatingFromDecimal',
            'helpful_votes'             => 'setHelpfulVotes',
            'comments'                  => 'setComments',
            'review_country'            => 'setReviewCountry',
            'is_global_review'          => 'setIsGlobalReview',
            'verified_purchase'         => 'setVerifiedPurchase',
            'vine_program'              => 'setVineProgram',
            'vine_program_free_product' => 'setVineProgramFree',
            'attributes'                => 'setAttributes',
            'date'                      => 'setReviewDateFromArray',
            'profile'                   => 'setProfileDetailsFromArray',
            'manufacturer_reply'        => 'setMfgReplyDetailsFromArray',
        ];

        foreach ($mapping as $dataField => $setter) {
            if (isset($data[$dataField])) {
                $this->$setter($data[$dataField]);
            } else {
                $this->$setter(null);
            }
        }
    }

    protected function setRatingFromDecimal($decimal) {
        $this->setRating50(10 * $decimal);
    }
    protected function convertDateArrayIntoDateObject($dateArray): ?\DateTime
    {
        if (empty($dateArray) || empty($dateArray['utc'])) {
            return null;
        }
        try {
            return new \DateTime($dateArray['utc']);
        } catch (\Exception $e) {
            return null;
        }
    }
    protected function setReviewDateFromArray($dateArray) {
        $this->setReviewDate($this->convertDateArrayIntoDateObject($dateArray));
    }
    protected function setProfileDetailsFromArray($dateArray) {
        $this->setProfileName($dateArray['name'] ?? null);
        $this->setProfileLink($dateArray['link'] ?? null);
    }
    protected function setMfgReplyDetailsFromArray($dataArray) {
        $this->setMfgReplyBody($dataArray['body'] ?? null);
        if (isset($dataArray['date'])) {
            $this->setMfgReplyDate($this->convertDateArrayIntoDateObject($dataArray['date']));
        }
        $this->setMfgReplyProfileLink($dataArray['profile']['link'] ?? null);
        $this->setMfgReplyProfileName($dataArray['profile']['name'] ?? null);
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

    public function setIdString($value): static
    {
        $this->idString = $value;
        return $this;
    }
    public function setTitle($value): static
    {
        $this->title = $value;
        return $this;
    }
    public function setBody($value): static
    {
        $this->body = $value;
        return $this;
    }
    public function setLink($value): static
    {
        $this->link = $value;
        return $this;
    }
    public function setRating50($value): static
    {
        $this->rating50 = $value;
        return $this;
    }
    public function setHelpfulVotes($value): static
    {
        $this->helpfulVotes = $value;
        return $this;
    }
    public function setComments($value): static
    {
        $this->comments = $value;
        return $this;
    }
    public function setReviewCountry($value): static
    {
        $this->reviewCountry = $value;
        return $this;
    }
    public function setIsGlobalReview($value): static
    {
        $this->isGlobalReview = $value;
        return $this;
    }
    public function setVerifiedPurchase($value): static
    {
        $this->verifiedPurchase = $value;
        return $this;
    }
    public function setVineProgram($value): static
    {
        $this->vineProgram = $value;
        return $this;
    }
    public function setVineProgramFree($value): static
    {
        $this->vineProgramFree = $value;
        return $this;
    }
    public function setAttributes($value): static
    {
        $this->attributes = $value;
        return $this;
    }
    public function setReviewDate($value): static
    {
        $this->reviewDate = $value;
        return $this;
    }
    public function setProfileName($value): static
    {
        $this->profileName = $value;
        return $this;
    }
    public function setProfileLink($value): static
    {
        $this->profileLink = $value;
        return $this;
    }
    public function setMfgReplyBody($value): static
    {
        $this->mfgReplyBody = $value;
        return $this;
    }
    public function setMfgReplyDate($value): static
    {
        $this->mfgReplyDate = $value;
        return $this;
    }
    public function setMfgReplyProfileName($value): static
    {
        $this->mfgReplyProfileName = $value;
        return $this;
    }
    public function setMfgReplyProfileLink($value): static
    {
        $this->mfgReplyProfileLink = $value;
        return $this;
    }


    /**
     * Get idString
     *
     * @return string|null
     */
    public function getIdString(): ?string
    {
        return $this->idString;
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get body
     *
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Get link
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Get rating50
     *
     * @return int|null
     */
    public function getRating50(): ?int
    {
        return $this->rating50;
    }

    /**
     * Get helpfulVotes
     *
     * @return int|null
     */
    public function getHelpfulVotes(): ?int
    {
        return $this->helpfulVotes;
    }

    /**
     * Get comments
     *
     * @return int|null
     */
    public function getComments(): ?int
    {
        return $this->comments;
    }

    /**
     * Get reviewCountry
     *
     * @return string|null
     */
    public function getReviewCountry(): ?string
    {
        return $this->reviewCountry;
    }

    /**
     * Get isGlobalReview
     *
     * @return bool|null
     */
    public function getIsGlobalReview(): ?bool
    {
        return $this->isGlobalReview;
    }

    /**
     * Get verifiedPurchase
     *
     * @return bool|null
     */
    public function getVerifiedPurchase(): ?bool
    {
        return $this->verifiedPurchase;
    }

    /**
     * Get vineProgram
     *
     * @return bool|null
     */
    public function getVineProgram(): ?bool
    {
        return $this->vineProgram;
    }

    /**
     * Get vineProgramFree
     *
     * @return bool|null
     */
    public function getVineProgramFree(): ?bool
    {
        return $this->vineProgramFree;
    }

    /**
     * Get attributes
     *
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * Get reviewDate
     *
     * @return \DateTime|null
     */
    public function getReviewDate(): ?\DateTime
    {
        return $this->reviewDate;
    }

    /**
     * Get profileName
     *
     * @return string|null
     */
    public function getProfileName(): ?string
    {
        return $this->profileName;
    }

    /**
     * Get profileLink
     *
     * @return string|null
     */
    public function getProfileLink(): ?string
    {
        return $this->profileLink;
    }

    /**
     * Get mfgReplyBody
     *
     * @return string|null
     */
    public function getMfgReplyBody(): ?string
    {
        return $this->mfgReplyBody;
    }

    /**
     * Get mfgReplyDate
     *
     * @return \DateTime|null
     */
    public function getMfgReplyDate(): ?\DateTime
    {
        return $this->mfgReplyDate;
    }

    /**
     * Get mfgReplyProfileName
     *
     * @return string|null
     */
    public function getMfgReplyProfileName(): ?string
    {
        return $this->mfgReplyProfileName;
    }

    /**
     * Get mfgReplyProfileLink
     *
     * @return string|null
     */
    public function getMfgReplyProfileLink(): ?string
    {
        return $this->mfgReplyProfileLink;
    }
}
