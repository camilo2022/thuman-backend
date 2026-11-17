<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationStoreUseCase;
use App\Http\Requests\Vacant\Application\ApplicationStoreRequest;
use App\Http\Resources\Vacant\Application\ApplicationResource;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke(ApplicationStoreRequest $request)
    {
        try {
            $applicationStoreUseCase = new ApplicationStoreUseCase($this->applicationRepository);
            $application = new ApplicationResource($applicationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $application,
                'La aplicación fue registrada exitosamente.',
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
