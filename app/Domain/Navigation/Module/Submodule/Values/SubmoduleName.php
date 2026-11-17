<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmoduleName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
