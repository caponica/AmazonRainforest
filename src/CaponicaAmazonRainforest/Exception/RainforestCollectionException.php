<?php

namespace CaponicaAmazonRainforest\Exception;

class RainforestCollectionException extends \RuntimeException
{
    private ?int $httpCode;

    public function __construct(string $message, ?int $httpCode = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->httpCode = $httpCode;
    }

    public function getHttpCode(): ?int
    {
        return $this->httpCode;
    }
}
