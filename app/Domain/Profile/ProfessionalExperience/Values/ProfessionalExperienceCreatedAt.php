<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceCreatedAt
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
