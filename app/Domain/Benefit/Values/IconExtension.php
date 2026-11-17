<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Values;

final class IconExtension
{
    private $value;

    public function __construct(string $extension)
    {
        $this->value = $extension;
    }

    public function value(): string
    {
        return $this->value;
    }
}
