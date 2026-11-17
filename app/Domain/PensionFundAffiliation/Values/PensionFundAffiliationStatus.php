<?php

declare(strict_types=1);

namespace App\Domain\PensionFundAffiliation\Values;

final class PensionFundAffiliationStatus
{
    private $value;

    public function __construct(string|null $status)
    {
        $this->value = $status;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
