<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Values;

final class VacationPersonId
{
    private $value;

    public function __construct(int $personId)
    {
        $this->value = $personId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
