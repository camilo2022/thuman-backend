<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractPositionId
{
    private $value;

    public function __construct(int $positionId)
    {
        $this->value = $positionId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
