<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation\Values;

final class CompensationFundAffiliationId
{
    private $value;

    public function __construct(int|null $id = null)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
