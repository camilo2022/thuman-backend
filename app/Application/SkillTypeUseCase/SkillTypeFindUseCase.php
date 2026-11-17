<?php

declare(strict_types=1);

namespace App\Application\SkillTypeUseCase;

use App\Domain\SkillType\SkillType;
use App\Domain\SkillType\Contracts\SkillTypeContract;
use App\Domain\SkillType\Values\SkillTypeId;

final class SkillTypeFindUseCase
{
    private $skillTypeContract;

    public function __construct(SkillTypeContract $skillTypeContract)
    {
        $this->skillTypeContract = $skillTypeContract;
    }

    public function __invoke(SkillTypeId $skillTypeId): ?SkillType
    {
        return $this->skillTypeContract->find($skillTypeId);
    }
}
