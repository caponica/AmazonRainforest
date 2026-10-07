<?php

namespace CaponicaAmazonRainforest\Entity;

class RainforestCollection
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

    public function getName(): ?string
    {
        return $this->data['name'] ?? null;
    }

    public function getStatus(): ?string
    {
        return $this->data['status'] ?? null;
    }

    public function isBusy(): bool
    {
        return in_array($this->getStatus(), ['queued', 'running'], true);
    }

    public function getRequestsTotalCount(): int
    {
        return (int) ($this->data['requests_total_count'] ?? $this->data['request_total_count'] ?? 0);
    }

    public function getRequestsPageCount(): int
    {
        return (int) ($this->data['requests_page_count'] ?? $this->data['request_page_count'] ?? 0);
    }

    public function getNextResultSetId(): ?int
    {
        return isset($this->data['next_result_set_id']) ? (int) $this->data['next_result_set_id'] : null;
    }

    public function getDataAsLastResort(): array
    {
        return $this->data;
    }
}
