<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmoduleIcon
{
    private $value;

    public function __construct(string $icon)
    {
        $this->value = $icon;
    }

    public function value(): string
    {
        return $this->value;
    }
}
