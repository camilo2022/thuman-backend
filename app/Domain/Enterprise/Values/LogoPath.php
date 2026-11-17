<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Values;

final class LogoPath
{
    private $value;

    public function __construct(string $path)
    {
        $this->value = $path;
    }

    public function value(): string
    {
        return $this->value;
    }
}
