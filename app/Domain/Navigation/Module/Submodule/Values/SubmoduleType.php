<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmoduleType
{
    private $value;

    public function __construct(string|null $type)
    {
        $this->value = $type;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
