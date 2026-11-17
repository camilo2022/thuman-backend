<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractLegalRepresentativeSignedAt
{
    private $value;

    public function __construct(string|null $legalRepresentativeSignedAt)
    {
        $this->value = $legalRepresentativeSignedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
