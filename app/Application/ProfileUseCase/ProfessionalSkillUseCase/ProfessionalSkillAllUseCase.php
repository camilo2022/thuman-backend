<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\ProfessionalSkillUseCase;

use App\Domain\Profile\ProfessionalSkill\Contracts\ProfessionalSkillContract;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfessionalSkill\ProfessionalSkillAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProfessionalSkillAllUseCase
{
    private $professionalSkillContract;

    public function __construct(ProfessionalSkillContract $professionalSkillContract)
    {
        $this->professionalSkillContract = $professionalSkillContract;
    }

    public function __invoke(ProfessionalSkillAllRequest $request, ProfileId $profileId): LengthAwarePaginator
    {
        return $this->professionalSkillContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $profileId);
    }
}
