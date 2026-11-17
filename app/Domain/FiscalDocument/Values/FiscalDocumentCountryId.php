<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocument\Values;

final class FiscalDocumentCountryId
{
    private $value;

    public function __construct(int|null $countryId = null)
    {
        $this->value = $countryId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
