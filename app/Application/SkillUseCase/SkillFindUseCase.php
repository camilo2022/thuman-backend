<?php

declare(strict_types=1);

namespace App\Application\SkillUseCase;

use App\Domain\Skill\Skill;
use App\Domain\Skill\Contracts\SkillContract;
use App\Domain\Skill\Values\SkillId;

final class SkillFindUseCase
{
    private $skillContract;

    public function __construct(SkillContract $skillContract)
    {
        $this->skillContract = $skillContract;
    }

    public function __invoke(SkillId $skillId): ?Skill
    {
        return $this->skillContract->find($skillId);
    }
}
