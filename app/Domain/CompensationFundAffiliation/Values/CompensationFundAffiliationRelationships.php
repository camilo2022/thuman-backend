<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation\Values;

final class CompensationFundAffiliationRelationships
{
    private ?object $person;
    private ?object $country;
    private ?object $compensation_fund;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $country = null,
        ?object $compensation_fund = null,
        ?object $file = null
    ) {
        $this->person = $person;
        $this->country = $country;
        $this->compensation_fund = $compensation_fund;
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

    public function compensationFund(): ?array
    {
        return $this->compensation_fund;
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
            'compensation_fund' => $this->compensation_fund,
            'file' => $this->file
        ];
    }
}
