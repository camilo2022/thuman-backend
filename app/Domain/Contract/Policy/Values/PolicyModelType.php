<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyModelType
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
