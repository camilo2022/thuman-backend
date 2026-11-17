<?php

declare(strict_types=1);

namespace App\Domain\Induction\Step\Values;

final class StepDescription
{
    private $value;

    public function __construct(string $description)
    {
        $this->value = $description;
    }

    public function value(): string
    {
        return $this->value;
    }
}
