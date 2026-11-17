<?php

declare(strict_types=1);

namespace App\Domain\Location\Departament\Contracts;

use App\Domain\Location\Departament\Departament;
use App\Domain\Location\Departament\Values\DepartamentId;
use App\Http\Requests\Location\Departament\DepartamentAllRequest;
use App\Http\Requests\Location\Departament\DepartamentFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface DepartamentContract
{
    public function all(?string $search, ?int $perPage, DepartamentAllRequest $request): LengthAwarePaginator;

    public function find(DepartamentFindRequest $request, DepartamentId $departamentId): ?Departament;
}
