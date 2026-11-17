<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumEndDate
{
    private $value;

    public function __construct(string|null $endDate)
    {
        $this->value = $endDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
