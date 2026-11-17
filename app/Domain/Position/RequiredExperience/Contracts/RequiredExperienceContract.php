<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredExperience\Contracts;

use App\Domain\Position\RequiredExperience\RequiredExperience;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;
use App\Domain\Position\Values\PositionId;
use Illuminate\Pagination\LengthAwarePaginator;

interface RequiredExperienceContract
{
    public function all(string|null $search, int|null $perPage, PositionId $posiPositionId): LengthAwarePaginator;

    public function find(RequiredExperienceId $professionalExperienceId): ?RequiredExperience;

    public function store(RequiredExperience $professionalExperience): RequiredExperience;

    public function update(RequiredExperience $professionalExperience, RequiredExperienceId $professionalExperienceId): RequiredExperience;

    public function delete(RequiredExperienceId $professionalExperienceId): void;
}
