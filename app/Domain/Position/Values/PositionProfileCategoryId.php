<?php

declare(strict_types=1);

namespace App\Domain\Position\Values;

final class PositionProfileCategoryId
{
    private $value;

    public function __construct(int $profileCategoryId)
    {
        $this->value = $profileCategoryId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
