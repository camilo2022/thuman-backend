<?php

declare(strict_types=1);

namespace App\Domain\PensionFundAffiliation\Values;

final class PensionFundAffiliationRelationships
{
    private ?object $person;
    private ?object $country;
    private ?object $pension_fund;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $country = null,
        ?object $pension_fund = null,
        ?object $file = null
    ) {
        $this->person = $person;
        $this->country = $country;
        $this->pension_fund = $pension_fund;
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

    public function pensionFund(): ?array
    {
        return $this->pension_fund;
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
            'pension_fund' => $this->pension_fund,
            'file' => $this->file
        ];
    }
}
