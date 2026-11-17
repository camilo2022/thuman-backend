<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Values;

final class ModuleRoute
{
    private $value;

    public function __construct(string $route)
    {
        $this->value = $route;
    }

    public function value(): string
    {
        return $this->value;
    }
}
