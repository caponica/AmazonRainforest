<?php

namespace CaponicaAmazonRainforest\Entity;

class RainforestCollectionRequest
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId(): string
    {
        return (string) $this->data['id'];
    }

    public function getCustomId(): ?string
    {
        return isset($this->data['custom_id']) ? (string) $this->data['custom_id'] : null;
    }

    public function getType(): ?string
    {
        return $this->data['type'] ?? null;
    }

    public function getAmazonDomain(): ?string
    {
        return $this->data['amazon_domain'] ?? null;
    }

    public function getAsin(): ?string
    {
        return $this->data['asin'] ?? null;
    }
}
