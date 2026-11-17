<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class FilePath
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
