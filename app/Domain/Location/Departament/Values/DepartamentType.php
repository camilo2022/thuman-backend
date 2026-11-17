<?php

declare(strict_types=1);

namespace App\Domain\Location\Departament\Values;

final class DepartamentType
{
    private $value;
    
    public function __construct(string $type)
    {
        $this->value = $type;
    }

    public function value(): string
    {
        return $this->value;
    }
}