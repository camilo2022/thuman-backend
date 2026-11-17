<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Contracts;

use App\Domain\Vacation\Vacation;
use App\Domain\Vacation\Values\VacationId;
use App\Http\Requests\Vacation\VacationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface VacationContract
{
    public function all(string|null $search, int|null $perPage, VacationAllRequest $request): LengthAwarePaginator;

    public function find(VacationId $vacationId): ?Vacation;

    public function store(Vacation $vacation): Vacation;

    public function update(Vacation $vacation, VacationId $vacationId): Vacation;

    public function availability(int $personId): object;
}
