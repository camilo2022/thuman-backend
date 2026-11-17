<?php

declare(strict_types=1);

namespace App\Domain\Form\Values;

final class FormFormTypeId
{
    private $value;

    public function __construct(int $formTypeId)
    {
        $this->value = $formTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
