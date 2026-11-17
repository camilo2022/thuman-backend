<?php

declare(strict_types=1);

namespace App\Domain\Resignation\Values;

final class ResignationDate
{
    private $value;

    public function __construct(string $date)
    {
        $this->value = $date;
    }

    public function value(): string
    {
        return $this->value;
    }
}
