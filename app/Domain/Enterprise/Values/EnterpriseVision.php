<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Values;

final class EnterpriseVision
{
    private $value;
    
    public function __construct(string|null $vision)
    {
        $this->value = $vision;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}