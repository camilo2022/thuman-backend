<?php

declare(strict_types=1);

namespace App\Domain\Form\Values;

final class FormModelType
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
