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
    public function __construct($data = null) {
        if (!empty($data)) {
            $this->updateFromDataArray($data);
        }
    }

    /**
     * The original data array used to build this object - in case you need something not cleanly converted
     *
     * @var array $data
     */
    protected $data;

    protected $idString;
    protected $title;
    protected $body;
    protected $link;
    protected $rating50;
    protected $helpfulVotes;
    protected $comments;
    protected $verifiedPurchase;
    protected $attributes;
    protected $reviewDate;
    protected $profileName;
    protected $profileLink;
    protected $mfgReplyBody;
    protected $mfgReplyDate;
    protected $mfgReplyProfileName;
    protected $mfgReplyProfileLink;

    /**
     * @param array $data       Raw array of data from the API
     */
    public function updateFromDataArray($data) {
        $this->setData($data);
        $mapping = [
            'id'                    => 'setIdString',
            'title'                 => 'setTitle',
            'body'                  => 'setBody',
            'link'                  => 'setLink',
            'rating'                => 'setRatingFromDecimal',
            'helpful_votes'         => 'setHelpfulVotes',
            'comments'              => 'setComments',
            'verified_purchase'     => 'setVerifiedPurchase',
            'attributes'            => 'setAttributes',
            'date'                  => 'setReviewDateFromArray',
            'profile'               => 'setProfileDetailsFromArray',
            'manufacturer_reply'    => 'setMfgReplyDetailsFromArray',
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
    protected function convertDateArrayIntoDateObject($dateArray) {
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
        $this->setProfileName(isset($dateArray['name']) ? $dateArray['name'] : null);
        $this->setProfileLink(isset($dateArray['link']) ? $dateArray['link'] : null);
    }
    protected function setMfgReplyDetailsFromArray($dataArray) {
        $this->setMfgReplyBody(isset($dataArray['body']) ? $dataArray['body'] : null);
        if (isset($dataArray['date'])) {
            $this->setMfgReplyDate($this->convertDateArrayIntoDateObject($dataArray['date']));
        }
        $this->setMfgReplyProfileLink(isset($dataArray['profile']['link']) ? $dataArray['profile']['link'] : null);
        $this->setMfgReplyProfileName(isset($dataArray['profile']['name']) ? $dataArray['profile']['name'] : null);
    }


    protected function setData($value) {
        $this->data = $value;
        return $this;
    }
    public function getOriginalDataArray() {
        return $this->data;
    }

    public function setIdString($value) {
        $this->idString = $value;
        return $this;
    }
    public function setTitle($value) {
        $this->title = $value;
        return $this;
    }
    public function setBody($value) {
        $this->body = $value;
        return $this;
    }
    public function setLink($value) {
        $this->link = $value;
        return $this;
    }
    public function setRating50($value) {
        $this->rating50 = $value;
        return $this;
    }
    public function setHelpfulVotes($value) {
        $this->helpfulVotes = $value;
        return $this;
    }
    public function setComments($value) {
        $this->comments = $value;
        return $this;
    }
    public function setVerifiedPurchase($value) {
        $this->verifiedPurchase = $value;
        return $this;
    }
    public function setAttributes($value) {
        $this->attributes = $value;
        return $this;
    }
    public function setReviewDate($value) {
        $this->reviewDate = $value;
        return $this;
    }
    public function setProfileName($value) {
        $this->profileName = $value;
        return $this;
    }
    public function setProfileLink($value) {
        $this->profileLink = $value;
        return $this;
    }
    public function setMfgReplyBody($value) {
        $this->mfgReplyBody = $value;
        return $this;
    }
    public function setMfgReplyDate($value) {
        $this->mfgReplyDate = $value;
        return $this;
    }
    public function setMfgReplyProfileName($value) {
        $this->mfgReplyProfileName = $value;
        return $this;
    }
    public function setMfgReplyProfileLink($value) {
        $this->mfgReplyProfileLink = $value;
        return $this;
    }


    /**
     * Get idString
     *
     * @return string
     */
    public function getIdString()
    {
        return $this->idString;
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
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
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
     * Get rating50
     *
     * @return integer
     */
    public function getRating50()
    {
        return $this->rating50;
    }

    /**
     * Get helpfulVotes
     *
     * @return integer
     */
    public function getHelpfulVotes()
    {
        return $this->helpfulVotes;
    }

    /**
     * Get comments
     *
     * @return integer
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get verifiedPurchase
     *
     * @return boolean
     */
    public function getVerifiedPurchase()
    {
        return $this->verifiedPurchase;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get reviewDate
     *
     * @return \DateTime
     */
    public function getReviewDate()
    {
        return $this->reviewDate;
    }

    /**
     * Get profileName
     *
     * @return string
     */
    public function getProfileName()
    {
        return $this->profileName;
    }

    /**
     * Get profileLink
     *
     * @return string
     */
    public function getProfileLink()
    {
        return $this->profileLink;
    }

    /**
     * Get mfgReplyBody
     *
     * @return string
     */
    public function getMfgReplyBody()
    {
        return $this->mfgReplyBody;
    }

    /**
     * Get mfgReplyDate
     *
     * @return \DateTime
     */
    public function getMfgReplyDate()
    {
        return $this->mfgReplyDate;
    }

    /**
     * Get mfgReplyProfileName
     *
     * @return string
     */
    public function getMfgReplyProfileName()
    {
        return $this->mfgReplyProfileName;
    }

    /**
     * Get mfgReplyProfileLink
     *
     * @return string
     */
    public function getMfgReplyProfileLink()
    {
        return $this->mfgReplyProfileLink;
    }
}
