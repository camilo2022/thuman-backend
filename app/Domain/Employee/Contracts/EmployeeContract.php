<?php

declare(strict_types=1);

namespace App\Domain\Employee\Contracts;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Values\EmployeeId;
use App\Http\Requests\Employee\EmployeeAllRequest;
use App\Http\Requests\Employee\EmployeeFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmployeeContract
{
    public function all(string|null $search, int|null $perPage, EmployeeAllRequest $request): LengthAwarePaginator;

    public function find(EmployeeFindRequest $request, EmployeeId $employeeId): ?Employee;

    public function update(Employee $employee, EmployeeId $employeeId): Employee;
}
