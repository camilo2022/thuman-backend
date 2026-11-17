<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\RequiredSkillUseCase;

use App\Domain\Position\RequiredSkill\RequiredSkill;
use App\Domain\Position\RequiredSkill\Contracts\RequiredSkillContract;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPositionId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPercentage;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillSkillId;
use App\Http\Requests\Position\RequiredSkill\RequiredSkillUpdateRequest;

final class RequiredSkillUpdateUseCase
{
    private $requiredSkillContract;

    public function __construct(RequiredSkillContract $requiredSkillContract)
    {
        $this->requiredSkillContract = $requiredSkillContract;
    }

    public function __invoke(RequiredSkillUpdateRequest $request, RequiredSkillId $requiredSkillId): RequiredSkill
    {
        return $this->requiredSkillContract->update(
            new RequiredSkill(
                new RequiredSkillId($requiredSkillId->value()),
                new RequiredSkillPositionId($request->integer('position_id')),
                new RequiredSkillSkillId($request->integer('skill_id')),
                new RequiredSkillPercentage($request->integer('percentage'))
            ),
            $requiredSkillId
        );
    }
}
