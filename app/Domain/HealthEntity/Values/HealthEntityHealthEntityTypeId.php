<?php

declare(strict_types=1);

namespace App\Domain\HealthEntity\Values;

final class HealthEntityHealthEntityTypeId
{
    private $value;

    public function __construct(int $healthEntityTypeId)
    {
        $this->value = $healthEntityTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
