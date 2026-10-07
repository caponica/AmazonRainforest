<?php

namespace CaponicaAmazonRainforest\Entity;

class RainforestCollectionResultSet
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId(): int
    {
        return (int) $this->data['id'];
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->buildDate('started_at');
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->buildDate('ended_at');
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->buildDate('expires_at');
    }

    public function isComplete(): bool
    {
        return !is_null($this->getEndedAt());
    }

    public function isExpired(\DateTimeImmutable $now): bool
    {
        $expiresAt = $this->getExpiresAt();

        return !is_null($expiresAt) && $expiresAt < $now;
    }

    public function getResultsPageCount(): int
    {
        return (int) ($this->data['results_page_count'] ?? 0);
    }

    public function getRequestsCompleted(): int
    {
        return (int) ($this->data['requests_completed'] ?? 0);
    }

    public function getRequestsFailed(): int
    {
        return (int) ($this->data['requests_failed'] ?? 0);
    }

    public function getRequestsTotal(): int
    {
        return (int) ($this->data['requests_total'] ?? 0);
    }

    private function buildDate(string $key): ?\DateTimeImmutable
    {
        if (empty($this->data[$key])) {
            return null;
        }

        return new \DateTimeImmutable($this->data[$key]);
    }
}
