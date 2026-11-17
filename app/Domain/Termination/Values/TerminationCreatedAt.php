<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class TerminationCreatedAt
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
