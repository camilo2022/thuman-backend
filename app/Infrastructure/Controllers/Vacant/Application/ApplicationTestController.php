<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationTestUseCase;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Http\Requests\Vacant\Application\ApplicationTestRequest;
use App\Http\Resources\Vacant\Application\ApplicationResource;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationTestController
{
    use ApiResponser;
    use ApiMessage;
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke(ApplicationTestRequest $request, int $applicationId)
    {
        try {
            $applicationId = new ApplicationId($applicationId ?? null);
            $applicationTestUseCase = new ApplicationTestUseCase($this->applicationRepository);
            $application = new ApplicationResource($applicationTestUseCase->__invoke($request, $applicationId));
            return $this->successResponse(
                $application,
                'La aplicación fue actualizada exitosamente a: PRUEBA.',
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
