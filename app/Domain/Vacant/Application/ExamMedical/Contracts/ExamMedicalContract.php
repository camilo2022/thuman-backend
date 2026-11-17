<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Contracts;

use App\Domain\Vacant\Application\ExamMedical\ExamMedical;
use App\Domain\Vacant\Application\ExamMedical\File;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalId;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalAllRequest;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ExamMedicalContract
{
    public function all(string|null $search, int|null $perPage, ExamMedicalAllRequest $request): LengthAwarePaginator;

    public function find(ExamMedicalFindRequest $request, ExamMedicalId $examMedicalId): ?ExamMedical;

    public function store(ExamMedical $examMedical): ExamMedical;

    public function update(ExamMedical $examMedical, ExamMedicalId $examMedicalId): ExamMedical;

    public function file(File $file): File;
}
