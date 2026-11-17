<?php

declare(strict_types=1);

namespace App\Domain\Form\Values;

final class FormAssignableId
{
    private $value;

    public function __construct(int|null $assignableId)
    {
        $this->value = $assignableId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
