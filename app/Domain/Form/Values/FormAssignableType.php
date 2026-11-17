<?php

declare(strict_types=1);

namespace App\Domain\Form\Values;

final class FormAssignableType
{
    private $value;

    public function __construct(string|null $assignableType)
    {
        $this->value = $assignableType;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
