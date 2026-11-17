<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application;

use App\Application\VacantUseCase\ApplicationUseCase\ApplicationNotifyTokenUseCase;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Http\Requests\Vacant\Application\ApplicationNotifyTokenRequest;
use App\Infrastructure\Repositories\ApplicationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ApplicationNotifyTokenController
{
    use ApiResponser;
    use ApiMessage;
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke(ApplicationNotifyTokenRequest $request, int $applicationId)
    {
        try {
            $applicationId = new ApplicationId($applicationId ?? null);
            $applicationNotifyTokenUseCase = new ApplicationNotifyTokenUseCase($this->applicationRepository);
            $applicationNotifyTokenUseCase->__invoke($applicationId);
            return $this->successResponse(
                '',
                'Se ha enviado exitosamente el código de acceso para presentar las pruebas al correo electrónico asociado a la persona.',
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
