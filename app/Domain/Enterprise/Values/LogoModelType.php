<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Values;

final class LogoModelType
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
