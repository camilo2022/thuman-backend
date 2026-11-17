<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountrySubregionId
{
    private $value;
    
    public function __construct(int|null $subregionId)
    {
        $this->value = $subregionId;
    }

    public function value(): int|null
    {
        return $this->value;
    }

}