<?php

declare(strict_types=1);

namespace App\Application\EmployeeUseCase;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Contracts\EmployeeContract;
use App\Domain\Employee\Values\EmployeeId;
use App\Http\Requests\Employee\EmployeeFindRequest;

final class EmployeeFindUseCase
{
    private $employeeContract;

    public function __construct(EmployeeContract $employeeContract)
    {
        $this->employeeContract = $employeeContract;
    }

    public function __invoke(EmployeeFindRequest $request, EmployeeId $employeeId): ?Employee
    {
        return $this->employeeContract->find($request, $employeeId);
    }
}
