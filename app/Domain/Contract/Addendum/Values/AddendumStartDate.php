<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumStartDate
{
    private $value;

    public function __construct(string|null $startDate)
    {
        $this->value = $startDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
