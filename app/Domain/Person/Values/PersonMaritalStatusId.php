<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonMaritalStatusId
{
    private $value;
    
    public function __construct(int|null $personMaritalStautsId)
    {
        $this->value = $personMaritalStautsId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}