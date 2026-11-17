<?php

declare(strict_types=1);

namespace App\Domain\FormType\Values;

final class FormTypeId
{
    private $value;

    public function __construct(int|null $id)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
