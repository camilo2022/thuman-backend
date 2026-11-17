<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredSkill\Contracts;

use App\Domain\Position\RequiredSkill\RequiredSkill;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;
use App\Domain\Position\Values\PositionId;
use Illuminate\Pagination\LengthAwarePaginator;

interface RequiredSkillContract
{
    public function all(string|null $search, int|null $perPage, PositionId $positionId): LengthAwarePaginator;

    public function find(RequiredSkillId $requiredSkillId): ?RequiredSkill;

    public function store(RequiredSkill $requiredSkill): RequiredSkill;

    public function update(RequiredSkill $requiredSkill, RequiredSkillId $requiredSkillId): RequiredSkill;

    public function delete(RequiredSkillId $requiredSkillId): void;
}
