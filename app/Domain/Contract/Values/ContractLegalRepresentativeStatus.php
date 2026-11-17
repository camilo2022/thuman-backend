<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractLegalRepresentativeStatus
{
    private $value;

    public function __construct(string|null $legalRepresentativeStatus)
    {
        $this->value = $legalRepresentativeStatus;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
