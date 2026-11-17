<?php

declare(strict_types=1);

namespace App\Domain\Position\Values;

final class PositionAreaId
{
    private $value;

    public function __construct(int $areaId)
    {
        $this->value = $areaId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
