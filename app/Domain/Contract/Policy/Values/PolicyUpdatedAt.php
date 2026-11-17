<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy\Values;

final class PolicyUpdatedAt
{
    private $value;

    public function __construct(string $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string
    {
        return $this->value;
    }
}
