<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\ProfessionalSkillUseCase;

use App\Domain\Profile\ProfessionalSkill\ProfessionalSkill;
use App\Domain\Profile\ProfessionalSkill\Contracts\ProfessionalSkillContract;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;

final class ProfessionalSkillFindUseCase
{
    private $professionalSkillContract;

    public function __construct(ProfessionalSkillContract $professionalSkillContract)
    {
        $this->professionalSkillContract = $professionalSkillContract;
    }

    public function __invoke(ProfessionalSkillId $professionalSkillId): ?ProfessionalSkill
    {
        return $this->professionalSkillContract->find($professionalSkillId);
    }
}
