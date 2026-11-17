<?php

declare(strict_types=1);

namespace App\Domain\Absence\Contracts;

use App\Domain\Absence\Absence;
use App\Domain\Absence\File;
use App\Domain\Absence\Values\AbsenceId;
use App\Http\Requests\Absence\AbsenceAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface AbsenceContract
{
    public function all(string|null $search, int|null $perPage, AbsenceAllRequest $request): LengthAwarePaginator;

    public function find(AbsenceId $absenceId): ?Absence;

    public function store(Absence $absence): Absence;

    public function update(Absence $absence, AbsenceId $absenceId): Absence;

    public function file(File $file): File;
}
