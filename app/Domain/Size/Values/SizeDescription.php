<?php

declare(strict_types=1);

namespace App\Domain\Size\Values;

final class SizeDescription
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
