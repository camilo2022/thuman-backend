<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceProfileId
{
    private $value;

    public function __construct(int $profileId)
    {
        $this->value = $profileId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
