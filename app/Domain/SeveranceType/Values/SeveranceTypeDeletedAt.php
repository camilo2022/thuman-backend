<?php

declare(strict_types=1);

namespace App\Domain\SeveranceType\Values;

final class SeveranceTypeDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
