<?php

declare(strict_types=1);

namespace App\Domain\Location\Departament\Values;

final class DepartamentStatusCode
{
    private $value;
    
    public function __construct(string|null $statusCode)
    {
        $this->value = $statusCode;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}