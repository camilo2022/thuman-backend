<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumLegalRepresentativeStatus
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
