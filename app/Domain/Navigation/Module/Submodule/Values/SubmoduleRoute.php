<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmoduleRoute
{
    private $value;

    public function __construct(string|null $route)
    {
        $this->value = $route;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
