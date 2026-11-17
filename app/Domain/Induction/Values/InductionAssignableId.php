<?php

declare(strict_types=1);

namespace App\Domain\Induction\Values;

final class InductionAssignableId
{
    private $value;

    public function __construct(int|null $assignableId)
    {
        $this->value = $assignableId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
