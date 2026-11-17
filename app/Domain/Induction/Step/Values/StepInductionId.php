<?php

declare(strict_types=1);

namespace App\Domain\Induction\Step\Values;

final class StepInductionId
{
    private $value;

    public function __construct(int $inductionId)
    {
        $this->value = $inductionId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
