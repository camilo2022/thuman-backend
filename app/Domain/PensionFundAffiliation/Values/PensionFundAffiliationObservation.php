<?php

declare(strict_types=1);

namespace App\Domain\PensionFundAffiliation\Values;

final class PensionFundAffiliationObservation
{
    private $value;

    public function __construct(string|null $observation)
    {
        $this->value = $observation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
