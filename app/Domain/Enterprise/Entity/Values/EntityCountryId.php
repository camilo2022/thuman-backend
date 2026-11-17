<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityCountryId
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