<?php

declare(strict_types=1);

namespace App\Domain\Position\Responsability\Values;

final class ResponsabilityPositionId
{
    private $value;

    public function __construct(int $profileId)
    {
        $this->value = $profileId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
