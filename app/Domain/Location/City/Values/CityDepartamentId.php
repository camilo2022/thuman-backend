<?php

declare(strict_types=1);

namespace App\Domain\Location\City\Values;

final class CityDepartamentId
{
    private $value;
    
    public function __construct(int|null $departamentId)
    {
        $this->value = $departamentId;
    }

    public function value(): int|null
    {
        return $this->value;
    }

}