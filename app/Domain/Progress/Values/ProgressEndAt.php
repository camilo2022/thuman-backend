<?php

declare(strict_types=1);

namespace App\Domain\Progress\Values;

final class ProgressEndAt
{
    private $value;

    public function __construct(string|null $endAt)
    {
        $this->value = $endAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
