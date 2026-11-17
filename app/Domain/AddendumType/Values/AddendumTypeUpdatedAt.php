<?php

declare(strict_types=1);

namespace App\Domain\AddendumType\Values;

final class AddendumTypeUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
