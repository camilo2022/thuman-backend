<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Values;

final class BenefitName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
