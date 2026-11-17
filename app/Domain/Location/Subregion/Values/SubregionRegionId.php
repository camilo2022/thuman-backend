<?php

declare(strict_types=1);

namespace App\Domain\Location\Subregion\Values;

final class SubregionRegionId
{
    private $value;
    
    public function __construct(int|null $regionId)
    {
        $this->value = $regionId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}