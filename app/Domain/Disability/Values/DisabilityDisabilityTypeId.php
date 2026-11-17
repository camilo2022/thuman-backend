<?php

declare(strict_types=1);

namespace App\Domain\Disability\Values;

final class DisabilityDisabilityTypeId
{
    private $value;

    public function __construct(int $disabilityTypeId)
    {
        $this->value = $disabilityTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
