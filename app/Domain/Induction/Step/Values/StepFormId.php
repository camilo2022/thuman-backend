<?php

declare(strict_types=1);

namespace App\Domain\Induction\Step\Values;

final class StepFormId
{
    private $value;

    public function __construct(int $formId)
    {
        $this->value = $formId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
