<?php

declare(strict_types=1);

namespace App\Domain\Severance\Values;

final class SeveranceSeveranceTypeId
{
    private $value;

    public function __construct(int $severanceTypeId)
    {
        $this->value = $severanceTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
