<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceWorkArrangementId
{
    private $value;

    public function __construct(int $workArrangementId)
    {
        $this->value = $workArrangementId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
