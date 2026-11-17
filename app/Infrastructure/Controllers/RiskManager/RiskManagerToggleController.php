<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\RiskManager;

use App\Application\RiskManagerUseCase\RiskManagerToggleUseCase;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Http\Requests\RiskManager\RiskManagerToggleRequest;
use App\Http\Resources\RiskManager\RiskManagerResource;
use App\Infrastructure\Repositories\RiskManagerRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RiskManagerToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $riskManagerRepository;

    public function __construct(RiskManagerRepository $riskManagerRepository)
    {
        $this->riskManagerRepository = $riskManagerRepository;
    }

    public function __invoke(RiskManagerToggleRequest $request, int $riskManagerId)
    {
        try {
            $riskManagerId = new RiskManagerId($riskManagerId ?? null);
            $riskManagerToggleUseCase = new RiskManagerToggleUseCase($this->riskManagerRepository);
            $riskManager = new RiskManagerResource($riskManagerToggleUseCase->__invoke($request, $riskManagerId));
            return $this->successResponse(
                $riskManager,
                'El país fue asignado/removido a la administradora de riesgo laboral exitosamente.',
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
