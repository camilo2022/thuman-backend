<?php

declare(strict_types=1);

namespace App\Domain\Resignation\Values;

final class ResignationReason
{
    private $value;

    public function __construct(string $reason)
    {
        $this->value = $reason;
    }

    public function value(): string
    {
        return $this->value;
    }
}
