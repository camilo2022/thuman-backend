<?php

declare(strict_types=1);

namespace App\Domain\Induction\Step\Values;

final class FileModelType
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
