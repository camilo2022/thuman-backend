<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonEntityId
{
    private $value;

    public function __construct(int|null $entityId = null)
    {
        $this->value = $entityId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
