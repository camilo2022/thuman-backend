<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonVacantId
{
    private $value;

    public function __construct(int|null $vacantId = null)
    {
        $this->value = $vacantId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
