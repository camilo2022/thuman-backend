<?php

declare(strict_types=1);

namespace App\Domain\Location\Departament\Values;

final class DepartamentCountryId
{
    private $value;
    
    public function __construct(int|null $countryId)
    {
        $this->value = $countryId;
    }

    public function value(): int|null
    {
        return $this->value;
    }

}