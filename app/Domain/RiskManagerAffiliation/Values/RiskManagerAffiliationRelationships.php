<?php

declare(strict_types=1);

namespace App\Domain\RiskManagerAffiliation\Values;

final class RiskManagerAffiliationRelationships
{
    private ?object $person;
    private ?object $country;
    private ?object $risk_manager;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $country = null,
        ?object $risk_manager = null,
        ?object $file = null
    ) {
        $this->person = $person;
        $this->country = $country;
        $this->risk_manager = $risk_manager;
        $this->file = $file;
    }

    public function person(): ?array
    {
        return $this->person;
    }

    public function country(): ?array
    {
        return $this->country;
    }

    public function riskManager(): ?array
    {
        return $this->risk_manager;
    }

    public function file(): ?array
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'country' => $this->country,
            'risk_manager' => $this->risk_manager,
            'file' => $this->file
        ];
    }
}
