<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Values;

final class IconModelType
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
