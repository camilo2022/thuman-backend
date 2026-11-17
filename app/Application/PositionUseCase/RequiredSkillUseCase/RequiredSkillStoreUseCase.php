<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\RequiredSkillUseCase;

use App\Domain\Position\RequiredSkill\Contracts\RequiredSkillContract;
use App\Domain\Position\RequiredSkill\RequiredSkill;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPositionId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPercentage;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillSkillId;
use App\Http\Requests\Position\RequiredSkill\RequiredSkillStoreRequest;

final class RequiredSkillStoreUseCase
{
    private $requiredSkillContract;

    public function __construct(RequiredSkillContract $requiredSkillContract)
    {
        $this->requiredSkillContract = $requiredSkillContract;
    }

    public function __invoke(RequiredSkillStoreRequest $request): RequiredSkill
    {
        return $this->requiredSkillContract->store(
            new RequiredSkill(
                new RequiredSkillId(null),
                new RequiredSkillPositionId($request->integer('position_id')),
                new RequiredSkillSkillId($request->integer('skill_id')),
                new RequiredSkillPercentage($request->integer('percentage'))
            )
        );
    }
}
