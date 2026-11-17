<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Employee;

use App\Application\EmployeeUseCase\EmployeeAllUseCase;
use App\Http\Requests\Employee\EmployeeAllRequest;
use App\Http\Resources\Employee\EmployeeCollection;
use App\Infrastructure\Repositories\EmployeeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EmployeeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function __invoke(EmployeeAllRequest $request)
    {
        try {
            $employeeAllUseCase = new EmployeeAllUseCase($this->employeeRepository);
            $employees = new EmployeeCollection($employeeAllUseCase->__invoke($request));
            return $this->successResponse(
                $employees,
                'Los empleados consultados exitosamente.',
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
