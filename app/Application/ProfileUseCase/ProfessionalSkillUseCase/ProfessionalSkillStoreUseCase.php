<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\ProfessionalSkillUseCase;

use App\Domain\Profile\ProfessionalSkill\Contracts\ProfessionalSkillContract;
use App\Domain\Profile\ProfessionalSkill\ProfessionalSkill;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillProfileId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillPercentage;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillSkillId;
use App\Http\Requests\Profile\ProfessionalSkill\ProfessionalSkillStoreRequest;

final class ProfessionalSkillStoreUseCase
{
    private $professionalSkillContract;

    public function __construct(ProfessionalSkillContract $professionalSkillContract)
    {
        $this->professionalSkillContract = $professionalSkillContract;
    }

    public function __invoke(ProfessionalSkillStoreRequest $request): ProfessionalSkill
    {
        return $this->professionalSkillContract->store(
            new ProfessionalSkill(
                new ProfessionalSkillId(null),
                new ProfessionalSkillProfileId($request->integer('profile_id')),
                new ProfessionalSkillSkillId($request->integer('skill_id')),
                new ProfessionalSkillPercentage($request->integer('percentage'))
            )
        );
    }
}
