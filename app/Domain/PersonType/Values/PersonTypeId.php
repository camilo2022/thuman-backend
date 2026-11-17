<?php

declare(strict_types=1);

namespace App\Domain\PersonType\Values;

final class PersonTypeId
{
    private $value;

    public function __construct(int|null $id)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
