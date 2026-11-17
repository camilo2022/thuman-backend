<?php

declare(strict_types=1);

namespace App\Domain\Location\Departament\Values;

final class DepartamentLatitude
{
    private $value;
    
    public function __construct(string|null $latitude)
    {
        $this->value = $latitude;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}