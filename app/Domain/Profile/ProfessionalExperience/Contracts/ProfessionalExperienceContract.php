<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Contracts;

use App\Domain\Profile\ProfessionalExperience\File;
use App\Domain\Profile\ProfessionalExperience\ProfessionalExperience;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceId;
use App\Domain\Profile\Values\ProfileId;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProfessionalExperienceContract
{
    public function all(string|null $search, int|null $perPage, ProfileId $profileId): LengthAwarePaginator;

    public function find(ProfessionalExperienceId $professionalExperienceId): ?ProfessionalExperience;

    public function store(ProfessionalExperience $professionalExperience): ProfessionalExperience;

    public function update(ProfessionalExperience $professionalExperience, ProfessionalExperienceId $professionalExperienceId): ProfessionalExperience;

    public function delete(ProfessionalExperienceId $professionalExperienceId): void;

    public function file(File $file): File;
}
