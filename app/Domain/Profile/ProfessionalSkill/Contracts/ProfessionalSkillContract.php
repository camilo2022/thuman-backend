<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalSkill\Contracts;

use App\Domain\Profile\ProfessionalSkill\ProfessionalSkill;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;
use App\Domain\Profile\Values\ProfileId;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProfessionalSkillContract
{
    public function all(string|null $search, int|null $perPage, ProfileId $profileId): LengthAwarePaginator;

    public function find(ProfessionalSkillId $professionalSkillId): ?ProfessionalSkill;

    public function store(ProfessionalSkill $professionalSkill): ProfessionalSkill;

    public function update(ProfessionalSkill $professionalSkill, ProfessionalSkillId $professionalSkillId): ProfessionalSkill;

    public function delete(ProfessionalSkillId $professionalSkillId): void;
}
