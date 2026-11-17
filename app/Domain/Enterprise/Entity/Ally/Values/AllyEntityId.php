<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Values;

final class AllyEntityId
{
    private $value;

    public function __construct(int|null $entityId)
    {
        $this->value = $entityId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
