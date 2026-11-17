<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Employee;

use App\Application\EmployeeUseCase\EmployeeFindUseCase;
use App\Domain\Employee\Values\EmployeeId;
use App\Http\Requests\Employee\EmployeeFindRequest;
use App\Http\Resources\Employee\EmployeeResource;
use App\Infrastructure\Repositories\EmployeeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EmployeeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function __invoke(EmployeeFindRequest $request, int|null $employeeId = null)
    {
        try {
            $employeeId = new EmployeeId($employeeId ?? null);
            $employeeFindUseCase = new EmployeeFindUseCase($this->employeeRepository);
            $employee = new EmployeeResource($employeeFindUseCase->__invoke($request, $employeeId));
            return $this->successResponse(
                $employee,
                'El empleado fue encontrada exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
