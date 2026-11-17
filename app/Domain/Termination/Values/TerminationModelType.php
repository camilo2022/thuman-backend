<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class TerminationModelType
{
    private $value;

    public function __construct(string|null $modelType)
    {
        $this->value = $modelType;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
