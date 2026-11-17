<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonBloodTypeId
{
    private $value;
    
    public function __construct(int|null $personBloodTypeId)
    {
        $this->value = $personBloodTypeId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}