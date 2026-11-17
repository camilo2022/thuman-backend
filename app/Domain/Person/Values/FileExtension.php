<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class FileExtension
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
