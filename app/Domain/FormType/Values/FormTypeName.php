<?php

declare(strict_types=1);

namespace App\Domain\FormType\Values;

final class FormTypeName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
