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
    protected ?string $asin;
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
    protected ?\DateTimeInterface $reviewDate;
    protected ?string $profileName;
    protected ?string $profileLink;
    protected ?string $mfgReplyBody;
    protected ?\DateTimeInterface $mfgReplyDate;
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
            'asin'                      => 'setAsin',
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
    protected function convertDateArrayIntoDateObject($dateArray): ?\DateTimeInterface
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

    public function setIdString(?string $value): static
    {
        $this->idString = $value;
        return $this;
    }
    public function setTitle(?string $value): static
    {
        // if title begins with "x.0 out of 5 stars" then strip that and trim the remaining value
        $pregResult = preg_match('/^[0-5]+\.0 out of 5 stars/', $value, $matches);
        if ($pregResult) {
            $value = trim(substr($value, strlen($matches[0])));
        }
        $this->title = $value;
        return $this;
    }
    public function setBody(?string $value): static
    {
        $this->body = $value;
        return $this;
    }
    public function setAsin(?string $value): static
    {
        $this->asin = $value;
        return $this;
    }
    public function setLink(?string $value): static
    {
        $this->link = $value;
        return $this;
    }
    public function setRating50(?int $value): static
    {
        $this->rating50 = $value;
        return $this;
    }
    public function setHelpfulVotes(?int $value): static
    {
        $this->helpfulVotes = $value;
        return $this;
    }
    public function setComments(?int $value): static
    {
        $this->comments = $value;
        return $this;
    }
    public function setReviewCountry(?string $value): static
    {
        $this->reviewCountry = $value;
        return $this;
    }
    public function setIsGlobalReview(?bool $value): static
    {
        $this->isGlobalReview = $value;
        return $this;
    }
    public function setVerifiedPurchase(?bool $value): static
    {
        $this->verifiedPurchase = $value;
        return $this;
    }
    public function setVineProgram(?bool $value): static
    {
        $this->vineProgram = $value;
        return $this;
    }
    public function setVineProgramFree(?bool $value): static
    {
        $this->vineProgramFree = $value;
        return $this;
    }
    public function setAttributes(?array $value): static
    {
        $this->attributes = $value;
        return $this;
    }
    public function setReviewDate(?\DateTimeInterface $value): static
    {
        $this->reviewDate = $value;
        return $this;
    }
    public function setProfileName(?string $value): static
    {
        $this->profileName = $value;
        return $this;
    }
    public function setProfileLink(?string $value): static
    {
        $this->profileLink = $value;
        return $this;
    }
    public function setMfgReplyBody(?string $value): static
    {
        $this->mfgReplyBody = $value;
        return $this;
    }
    public function setMfgReplyDate(?\DateTimeInterface $value): static
    {
        $this->mfgReplyDate = $value;
        return $this;
    }
    public function setMfgReplyProfileName(?string $value): static
    {
        $this->mfgReplyProfileName = $value;
        return $this;
    }
    public function setMfgReplyProfileLink(?string $value): static
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
     * Get ASIN
     *
     * @return string|null
     */
    public function getAsin(): ?string
    {
        return $this->asin;
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
     * @return \DateTimeInterface|null
     */
    public function getReviewDate(): ?\DateTimeInterface
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
     * @return \DateTimeInterface|null
     */
    public function getMfgReplyDate(): ?\DateTimeInterface
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
