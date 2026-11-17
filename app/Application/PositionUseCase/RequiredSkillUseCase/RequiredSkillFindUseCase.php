<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\RequiredSkillUseCase;

use App\Domain\Position\RequiredSkill\RequiredSkill;
use App\Domain\Position\RequiredSkill\Contracts\RequiredSkillContract;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;

final class RequiredSkillFindUseCase
{
    private $requiredSkillContract;

    public function __construct(RequiredSkillContract $requiredSkillContract)
    {
        $this->requiredSkillContract = $requiredSkillContract;
    }

    public function __invoke(RequiredSkillId $requiredSkillId): ?RequiredSkill
    {
        return $this->requiredSkillContract->find($requiredSkillId);
    }
}
