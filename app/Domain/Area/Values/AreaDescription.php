<?php

declare(strict_types=1);

namespace App\Domain\Area\Values;

final class AreaDescription
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
