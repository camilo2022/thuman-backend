<?php

declare(strict_types=1);

namespace App\Domain\Location\City\Values;

final class CityUpdatedAt
{
    private $value;
    
    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}