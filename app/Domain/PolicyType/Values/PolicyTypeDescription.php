<?php

declare(strict_types=1);

namespace App\Domain\PolicyType\Values;

final class PolicyTypeDescription
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
