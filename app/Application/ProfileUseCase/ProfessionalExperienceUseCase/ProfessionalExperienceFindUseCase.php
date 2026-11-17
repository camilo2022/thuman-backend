<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\ProfessionalExperienceUseCase;

use App\Domain\Profile\ProfessionalExperience\ProfessionalExperience;
use App\Domain\Profile\ProfessionalExperience\Contracts\ProfessionalExperienceContract;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceId;

final class ProfessionalExperienceFindUseCase
{
    private $professionalExperienceContract;

    public function __construct(ProfessionalExperienceContract $professionalExperienceContract)
    {
        $this->professionalExperienceContract = $professionalExperienceContract;
    }

    public function __invoke(ProfessionalExperienceId $professionalExperienceId): ?ProfessionalExperience
    {
        return $this->professionalExperienceContract->find($professionalExperienceId);
    }
}
