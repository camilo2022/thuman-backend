<?php

declare(strict_types=1);

namespace App\Domain\Bank\Values;

final class BankDescription
{
    private $value;

    public function __construct(string $description)
    {
        $this->value = $description;
    }

    public function value(): string
    {
        return $this->value;
    }
}
