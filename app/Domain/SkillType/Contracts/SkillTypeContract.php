<?php

declare(strict_types=1);

namespace App\Domain\SkillType\Contracts;

use App\Domain\SkillType\SkillType;
use App\Domain\SkillType\Values\SkillTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface SkillTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(SkillTypeId $skillTypeId): ?SkillType;
}
