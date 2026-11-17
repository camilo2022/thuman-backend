<?php

declare(strict_types=1);

namespace App\Domain\Position\AcademicRequirement\Contracts;

use App\Domain\Position\AcademicRequirement\AcademicRequirement;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;
use App\Domain\Position\Values\PositionId;
use Illuminate\Pagination\LengthAwarePaginator;

interface AcademicRequirementContract
{
    public function all(string|null $search, int|null $perPage, PositionId $positionId): LengthAwarePaginator;

    public function find(AcademicRequirementId $academicRequirementId): ?AcademicRequirement;

    public function store(AcademicRequirement $academicRequirement): AcademicRequirement;

    public function update(AcademicRequirement $academicRequirement, AcademicRequirementId $academicRequirementId): AcademicRequirement;

    public function delete(AcademicRequirementId $academicRequirementId): void;
}
