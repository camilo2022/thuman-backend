<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Values;

final class BankCertificateRelationships
{
    private ?object $person;
    private ?object $country;
    private ?object $bank;
    private ?object $bank_account_type;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $country = null,
        ?object $bank = null,
        ?object $bank_account_type = null,
        ?object $file = null
    ) {
        $this->person = $person;
        $this->country = $country;
        $this->bank = $bank;
        $this->bank_account_type = $bank_account_type;
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

    public function bank(): ?array
    {
        return $this->bank;
    }

    public function bankAccountType(): ?array
    {
        return $this->bank_account_type;
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
            'bank' => $this->bank,
            'bank_account_type' => $this->bank_account_type,
            'file' => $this->file
        ];
    }
}
