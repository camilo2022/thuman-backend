<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\RequiredExperienceUseCase;

use App\Domain\Position\RequiredExperience\Contracts\RequiredExperienceContract;
use App\Domain\Position\RequiredExperience\RequiredExperience;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceName;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceDescription;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceDurationMonths;
use App\Domain\Position\RequiredExperience\Values\RequiredExperiencePositionId;
use App\Http\Requests\Position\RequiredExperience\RequiredExperienceUpdateRequest;

final class RequiredExperienceUpdateUseCase
{
    private $requiredExperienceContract;

    public function __construct(RequiredExperienceContract $requiredExperienceContract)
    {
        $this->requiredExperienceContract = $requiredExperienceContract;
    }

    public function __invoke(RequiredExperienceUpdateRequest $request, RequiredExperienceId $requiredExperienceId): RequiredExperience
    {
        return $this->requiredExperienceContract->update(
            new RequiredExperience(
                new RequiredExperienceId($requiredExperienceId->value()),
                new RequiredExperiencePositionId($request->integer('position_id')),
                new RequiredExperienceName($request->input('name')),
                new RequiredExperienceDurationMonths($request->integer('duration_months')),
                new RequiredExperienceDescription($request->input('description'))
            ),
            $requiredExperienceId
        );
    }
}
