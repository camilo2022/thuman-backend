<?php

declare(strict_types=1);

namespace App\Domain\AcademicLevel\Contracts;

use App\Domain\AcademicLevel\AcademicLevel;
use App\Domain\AcademicLevel\Values\AcademicLevelId;
use App\Http\Requests\AcademicLevel\AcademicLevelAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface AcademicLevelContract
{
    public function all(string|null $search, int|null $perPage, AcademicLevelAllRequest $request): LengthAwarePaginator;

    public function find(AcademicLevelId $academicLevelId): ?AcademicLevel;

    public function store(AcademicLevel $academicLevel): AcademicLevel;

    public function update(AcademicLevel $academicLevel, AcademicLevelId $academicLevelId): AcademicLevel;

    public function delete(AcademicLevelId $academicLevelId): AcademicLevel;

    public function restore(AcademicLevelId $academicLevelId): AcademicLevel;
}
