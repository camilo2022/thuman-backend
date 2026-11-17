<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class EntityEnterpriseId
{
    private $value;
    
    public function __construct(int|null $enterpriseId)
    {
        $this->value = $enterpriseId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}