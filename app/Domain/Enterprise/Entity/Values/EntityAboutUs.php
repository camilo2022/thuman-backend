<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityAboutUs
{
    private $value;
    
    public function __construct(string|null $aboutUs)
    {
        $this->value = $aboutUs;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}