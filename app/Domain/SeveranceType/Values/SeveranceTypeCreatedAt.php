<?php

declare(strict_types=1);

namespace App\Domain\SeveranceType\Values;

final class SeveranceTypeCreatedAt
{
    private $value;

    public function __construct(string|null $createdAt)
    {
        $this->value = $createdAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
