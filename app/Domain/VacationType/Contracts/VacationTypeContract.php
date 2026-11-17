<?php

declare(strict_types=1);

namespace App\Domain\VacationType\Contracts;

use App\Domain\VacationType\VacationType;
use App\Domain\VacationType\Values\VacationTypeId;
use App\Http\Requests\VacationType\VacationTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface VacationTypeContract
{
    public function all(string|null $search, int|null $perPage, VacationTypeAllRequest $request): LengthAwarePaginator;

    public function find(VacationTypeId $vacationTypeId): ?VacationType;

    public function store(VacationType $vacationType): VacationType;

    public function update(VacationType $vacationType, VacationTypeId $vacationTypeId): VacationType;

    public function delete(VacationTypeId $vacationTypeId): VacationType;

    public function restore(VacationTypeId $vacationTypeId): VacationType;
}
