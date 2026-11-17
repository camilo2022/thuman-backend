<?php

declare(strict_types=1);

namespace App\Domain\Position\Responsability\Contracts;

use App\Domain\Position\Responsability\Responsability;
use App\Domain\Position\Responsability\Values\ResponsabilityId;
use App\Domain\Position\Values\PositionId;
use Illuminate\Pagination\LengthAwarePaginator;

interface ResponsabilityContract
{
    public function all(string|null $search, int|null $perPage, PositionId $posiPositionId): LengthAwarePaginator;

    public function find(ResponsabilityId $professionalExperienceId): ?Responsability;

    public function store(Responsability $professionalExperience): Responsability;

    public function update(Responsability $professionalExperience, ResponsabilityId $professionalExperienceId): Responsability;

    public function delete(ResponsabilityId $professionalExperienceId): void;
}
