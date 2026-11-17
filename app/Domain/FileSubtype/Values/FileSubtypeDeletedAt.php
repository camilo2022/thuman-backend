<?php

declare(strict_types=1);

namespace App\Domain\FileSubtype\Values;

final class FileSubtypeDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
