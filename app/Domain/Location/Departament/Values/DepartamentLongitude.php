<?php

declare(strict_types=1);

namespace App\Domain\Location\Departament\Values;

final class DepartamentLongitude
{
    private $value;
    
    public function __construct(string|null $longitude)
    {
        $this->value = $longitude;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}