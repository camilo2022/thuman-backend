<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Values;

final class ModuleType
{
    private $value;

    public function __construct(string $type)
    {
        $this->value = $type;
    }

    public function value(): string
    {
        return $this->value;
    }
}
