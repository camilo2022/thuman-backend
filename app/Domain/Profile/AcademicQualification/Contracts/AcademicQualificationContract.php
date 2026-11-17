<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification\Contracts;

use App\Domain\Profile\AcademicQualification\AcademicQualification;
use App\Domain\Profile\AcademicQualification\File;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;
use App\Domain\Profile\Values\ProfileId;
use Illuminate\Pagination\LengthAwarePaginator;

interface AcademicQualificationContract
{
    public function all(string|null $search, int|null $perPage, ProfileId $profileId): LengthAwarePaginator;

    public function find(AcademicQualificationId $academicQualificationId): ?AcademicQualification;

    public function store(AcademicQualification $academicQualification): AcademicQualification;

    public function update(AcademicQualification $academicQualification, AcademicQualificationId $academicQualificationId): AcademicQualification;

    public function delete(AcademicQualificationId $academicQualificationId): void;

    public function file(File $file): File;
}
