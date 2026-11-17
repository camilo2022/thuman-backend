<?php

declare(strict_types=1);

namespace App\Domain\Profile\EmergencyContact\Values;

final class EmergencyContactRelationships
{
    private ?object $profile;
    private ?object $relationship;

    public function __construct(
        ?object $profile = null,
        ?object $relationship = null
    ) {
        $this->profile = $profile;
        $this->relationship = $relationship;
    }

    public function profile(): ?object
    {
        return $this->profile;
    }

    public function relationship(): ?object
    {
        return $this->relationship;
    }

    public function toArray(): array
    {
        return [
            'profile' => $this->profile,
            'relationship' => $this->relationship
        ];
    }
}
