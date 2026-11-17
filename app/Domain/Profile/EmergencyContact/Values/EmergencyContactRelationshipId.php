<?php

declare(strict_types=1);

namespace App\Domain\Profile\EmergencyContact\Values;

final class EmergencyContactRelationshipId
{
    private $value;

    public function __construct(int $relationshipId)
    {
        $this->value = $relationshipId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
