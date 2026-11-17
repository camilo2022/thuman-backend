<?php

declare(strict_types=1);

namespace App\Domain\Location\Subregion\Values;

final class SubregionCreatedAt
{
    private $value;
    
    public function __construct(string|null $createdAt)
    {
        $this->value = $createdAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}