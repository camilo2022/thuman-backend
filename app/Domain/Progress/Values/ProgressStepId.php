<?php

declare(strict_types=1);

namespace App\Domain\Progress\Values;

final class ProgressStepId
{
    private $value;

    public function __construct(int $stepId)
    {
        $this->value = $stepId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
