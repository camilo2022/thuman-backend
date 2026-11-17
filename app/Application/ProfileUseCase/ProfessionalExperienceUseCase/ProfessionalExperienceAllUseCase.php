<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\ProfessionalExperienceUseCase;

use App\Domain\Profile\ProfessionalExperience\Contracts\ProfessionalExperienceContract;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfessionalExperience\ProfessionalExperienceAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProfessionalExperienceAllUseCase
{
    private $professionalExperienceContract;

    public function __construct(ProfessionalExperienceContract $professionalExperienceContract)
    {
        $this->professionalExperienceContract = $professionalExperienceContract;
    }

    public function __invoke(ProfessionalExperienceAllRequest $request, ProfileId $profileId): LengthAwarePaginator
    {
        return $this->professionalExperienceContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $profileId);
    }
}
