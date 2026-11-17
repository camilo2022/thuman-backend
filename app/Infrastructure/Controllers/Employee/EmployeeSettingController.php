<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Employee;

use App\Application\EmployeeUseCase\EmployeeSettingUseCase;
use App\Domain\Employee\Values\EmployeeId;
use App\Http\Requests\Employee\EmployeeSettingRequest;
use App\Http\Resources\Employee\EmployeeResource;
use App\Infrastructure\Repositories\EmployeeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EmployeeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function __invoke(EmployeeSettingRequest $request, int $employeeId)
    {
        try {
            $employeeId = new EmployeeId($employeeId ?? null);
            $employeeSettingUseCase = new EmployeeSettingUseCase($this->employeeRepository);
            $employee = new EmployeeResource($employeeSettingUseCase->__invoke($request, $employeeId));
            return $this->successResponse(
                $employee,
                'La configuración del empleado fue realizada exitosamente.',
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
