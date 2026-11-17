<?php

declare(strict_types=1);

namespace App\Domain\PensionFundAffiliation\Values;

final class PensionFundAffiliationPensionFundId
{
    private $value;

    public function __construct(int $pensionFundId)
    {
        $this->value = $pensionFundId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
