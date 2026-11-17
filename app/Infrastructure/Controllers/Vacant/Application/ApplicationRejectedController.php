<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationRejectedUseCase;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Http\Requests\Vacant\Application\ApplicationRejectedRequest;
use App\Http\Resources\Vacant\Application\ApplicationResource;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke(ApplicationRejectedRequest $request, int $applicationId)
    {
        try {
            $applicationId = new ApplicationId($applicationId ?? null);
            $applicationRejectedUseCase = new ApplicationRejectedUseCase($this->applicationRepository);
            $application = new ApplicationResource($applicationRejectedUseCase->__invoke($request, $applicationId));
            return $this->successResponse(
                $application,
                'La aplicación fue actualizada exitosamente a: RECHAZADO.',
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
