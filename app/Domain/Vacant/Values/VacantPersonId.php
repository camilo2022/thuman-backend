<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantPersonId
{
    private $value;

    public function __construct(int|null $personId)
    {
        $this->value = $personId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
