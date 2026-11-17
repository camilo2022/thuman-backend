<?php

declare(strict_types=1);

namespace App\Application\SkillUseCase;

use App\Domain\Skill\Contracts\SkillContract;
use App\Domain\Skill\Skill;
use App\Domain\Skill\Values\SkillDescription;
use App\Domain\Skill\Values\SkillId;
use App\Domain\Skill\Values\SkillItemId;
use App\Domain\Skill\Values\SkillName;
use App\Domain\Skill\Values\SkillSettings;
use App\Domain\Skill\Values\SkillSkillTypeId;
use App\Http\Requests\Skill\SkillUpdateRequest;

final class SkillUpdateUseCase
{
    private $skillContract;

    public function __construct(SkillContract $skillContract)
    {
        $this->skillContract = $skillContract;
    }

    public function __invoke(SkillUpdateRequest $request, SkillId $skillId): Skill
    {
        return $this->skillContract->update(
            new Skill(
                new SkillId($skillId->value()),
                new SkillSkillTypeId($request->integer('skill_type_id')),
                new SkillItemId($request->integer('item_id')),
                new SkillName($request->input('name')),
                new SkillDescription($request->input('description')),
                new SkillSettings((object) [])
            ),
            $skillId
        );
    }
}
