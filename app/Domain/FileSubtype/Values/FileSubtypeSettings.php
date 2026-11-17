<?php

declare(strict_types=1);

namespace App\Domain\FileSubtype\Values;

final class FileSubtypeSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        $this->value = $settings;
    }

    public function value(): object|array
    {
        return $this->value;
    }

    public function variables(): array
    {
        return $this->value['variables'] ?? [];
    }
}
