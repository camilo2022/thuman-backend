<?php

declare(strict_types=1);

namespace App\Domain\Induction\Values;

final class InductionUpdatedAt
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
