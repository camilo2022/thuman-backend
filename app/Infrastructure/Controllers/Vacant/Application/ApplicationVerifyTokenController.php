<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationVerifyTokenUseCase;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Http\Requests\Vacant\Application\ApplicationVerifyTokenRequest;
use App\Http\Resources\Vacant\Application\ApplicationResource;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationVerifyTokenController
{
    use ApiResponser;
    use ApiMessage;
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke(ApplicationVerifyTokenRequest $request, int $applicationId)
    {
        try {
            $applicationId = new ApplicationId($applicationId ?? null);
            $applicationVerifyTokenUseCase = new ApplicationVerifyTokenUseCase($this->applicationRepository);
            $application = new ApplicationResource($applicationVerifyTokenUseCase->__invoke($request, $applicationId));
            return $this->successResponse(
                $application,
                'Se ha verificado exitosamente código de acceso para presentar las pruebas.',
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
