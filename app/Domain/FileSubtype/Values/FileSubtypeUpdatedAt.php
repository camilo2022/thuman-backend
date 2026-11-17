<?php

declare(strict_types=1);

namespace App\Domain\FileSubtype\Values;

final class FileSubtypeUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
