<?php

declare(strict_types=1);

namespace App\Domain\Progress\Values;

final class ProgressStartAt
{
    private $value;

    public function __construct(string|null $startAt)
    {
        $this->value = $startAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
