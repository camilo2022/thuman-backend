<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmoduleModelType
{
    private $value;

    public function __construct(string $modelType)
    {
        $this->value = $modelType;
    }

    public function value(): string
    {
        return $this->value;
    }
}
