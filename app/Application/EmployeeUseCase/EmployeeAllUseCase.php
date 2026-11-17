<?php

declare(strict_types=1);

namespace App\Application\EmployeeUseCase;

use App\Domain\Employee\Contracts\EmployeeContract;
use App\Http\Requests\Employee\EmployeeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class EmployeeAllUseCase
{
    private $employeeContract;

    public function __construct(EmployeeContract $employeeContract)
    {
        $this->employeeContract = $employeeContract;
    }

    public function __invoke(EmployeeAllRequest $request): LengthAwarePaginator
    {
        return $this->employeeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
