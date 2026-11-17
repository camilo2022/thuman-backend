<?php

declare(strict_types=1);

namespace App\Domain\Progress\Values;

final class ProgressStatus
{
    private $value;

    public function __construct(string $status)
    {
        $this->value = $status;
    }

    public function value(): string
    {
        return $this->value;
    }
}
