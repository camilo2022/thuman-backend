<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonSexId
{
    private $value;
    
    public function __construct(int|null $personSexId)
    {
        $this->value = $personSexId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}