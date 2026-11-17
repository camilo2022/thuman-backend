<?php

declare(strict_types=1);

namespace App\Domain\AbsenceType\Contracts;

use App\Domain\AbsenceType\AbsenceType;
use App\Domain\AbsenceType\Values\AbsenceTypeId;
use App\Http\Requests\AbsenceType\AbsenceTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface AbsenceTypeContract
{
    public function all(string|null $search, int|null $perPage, AbsenceTypeAllRequest $request): LengthAwarePaginator;

    public function find(AbsenceTypeId $absenceTypeId): ?AbsenceType;

    public function store(AbsenceType $absenceType): AbsenceType;

    public function update(AbsenceType $absenceType, AbsenceTypeId $absenceTypeId): AbsenceType;

    public function delete(AbsenceTypeId $absenceTypeId): AbsenceType;

    public function restore(AbsenceTypeId $absenceTypeId): AbsenceType;
}
