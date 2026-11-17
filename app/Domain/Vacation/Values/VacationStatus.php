<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Values;

final class VacationStatus
{
    private $value;

    public function __construct(string|null $status)
    {
        $this->value = $status;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
