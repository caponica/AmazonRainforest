<?php

namespace CaponicaAmazonRainforest\Entity;

use CaponicaAmazonRainforest\Response\CommonResponse;

abstract class RainforestEntityCommon
{
    abstract public function updateFromRainforestResponse(CommonResponse $rfResponse);

    /**
     * @param CommonResponse|null $rfResponse
     */
    public function __construct(CommonResponse $rfResponse = null) {
        $this->setRainforestResponse($rfResponse);
        if (!empty($rfResponse)) {
            $this->updateFromRainforestResponse($rfResponse);
        }
    }

    /**
     * @var CommonResponse
     */
    protected $rfResponse;

    /**
     * Stores the Response object used to create this Entity
     *
     * @param CommonResponse
     *
     * @return RainforestEntityCommon
     */
    public function setRainforestResponse($rfResponse)
    {
        $this->rfResponse = $rfResponse;

        return $this;
    }

    /**
     * Get the Response object used to create this Entity
     *
     * @return CommonResponse
     */
    public function getRainforestResponse()
    {
        return $this->rfResponse;
    }
}
