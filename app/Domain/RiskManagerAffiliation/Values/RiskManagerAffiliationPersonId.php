<?php

declare(strict_types=1);

namespace App\Domain\RiskManagerAffiliation\Values;

final class RiskManagerAffiliationPersonId
{
    private $value;

    public function __construct(int|null $personId = null)
    {
        $this->value = $personId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
