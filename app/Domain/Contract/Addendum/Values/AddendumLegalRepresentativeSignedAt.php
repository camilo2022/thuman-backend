<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumLegalRepresentativeSignedAt
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
