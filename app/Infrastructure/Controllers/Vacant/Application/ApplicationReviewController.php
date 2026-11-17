<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationReviewUseCase;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Http\Requests\Vacant\Application\ApplicationReviewRequest;
use App\Http\Resources\Vacant\Application\ApplicationResource;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationReviewController
{
    use ApiResponser;
    use ApiMessage;
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke(ApplicationReviewRequest $request, int $applicationId)
    {
        try {
            $applicationId = new ApplicationId($applicationId ?? null);
            $applicationReviewUseCase = new ApplicationReviewUseCase($this->applicationRepository);
            $application = new ApplicationResource($applicationReviewUseCase->__invoke($request, $applicationId));
            return $this->successResponse(
                $application,
                'La aplicación fue actualizada exitosamente a: REVISION.',
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
