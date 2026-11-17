<?php

declare(strict_types=1);

namespace App\Domain\Skill\Contracts;

use App\Domain\Skill\Skill;
use App\Domain\Skill\Values\SkillId;
use App\Http\Requests\Skill\SkillAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface SkillContract
{
    public function all(string|null $search, int|null $perPage, SkillAllRequest $request): LengthAwarePaginator;

    public function find(SkillId $skillId): ?Skill;

    public function store(Skill $skill): Skill;

    public function update(Skill $skill, SkillId $skillId): Skill;

    public function delete(SkillId $skillId): Skill;

    public function restore(SkillId $skillId): Skill;
}
