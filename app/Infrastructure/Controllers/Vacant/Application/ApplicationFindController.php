<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationFindUseCase;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Http\Requests\Vacant\Application\ApplicationFindRequest;
use App\Http\Resources\Vacant\Application\ApplicationResource;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationFindController
{
    use ApiResponser;
    use ApiMessage;
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke(ApplicationFindRequest $request, int|null $applicationId = null)
    {
        try {
            $applicationId = new ApplicationId($applicationId ?? null);
            $applicationFindUseCase = new ApplicationFindUseCase($this->applicationRepository);
            $application = new ApplicationResource($applicationFindUseCase->__invoke($request, $applicationId));
            return $this->successResponse(
                $application,
                'La aplicación fue encontrada exitosamente.',
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
