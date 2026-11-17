<?php

declare(strict_types=1);

namespace App\Application\EmployeeUseCase;

use App\Domain\Employee\Contracts\EmployeeContract;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Values\EmployeeEndDate;
use App\Domain\Employee\Values\EmployeeHeadquarterId;
use App\Domain\Employee\Values\EmployeeId;
use App\Domain\Employee\Values\EmployeePersonId;
use App\Domain\Employee\Values\EmployeePositionId;
use App\Domain\Employee\Values\EmployeeSettings;
use App\Domain\Employee\Values\EmployeeStartDate;
use App\Http\Requests\Employee\EmployeeSettingRequest;

final class EmployeeSettingUseCase
{
    private $employeeContract;

    public function __construct(EmployeeContract $employeeContract)
    {
        $this->employeeContract = $employeeContract;
    }

    public function __invoke(EmployeeSettingRequest $request, EmployeeId $employeeId): Employee
    {
        return $this->employeeContract->update(
            new Employee(
                new EmployeeId($employeeId->value()),
                new EmployeePersonId($request->integer('person_id')),
                new EmployeeHeadquarterId($request->integer('headquarter_id')),
                new EmployeePositionId($request->integer('position_id')),
                new EmployeeStartDate($request->input('start_date')),
                new EmployeeEndDate($request->input('end_date')),
                new EmployeeSettings((object) $request->input('settings'))
            ),
            $employeeId
        );
    }
}
