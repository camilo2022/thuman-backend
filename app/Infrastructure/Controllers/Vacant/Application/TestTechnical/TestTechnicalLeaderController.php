<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\TestTechnical;

use App\Application\VacantUseCase\ApplicationUseCase\TestTechnicalUseCase\TestTechnicalLeaderUseCase;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalLeaderRequest;
use App\Http\Resources\Vacant\Application\TestTechnical\TestTechnicalResource;
use App\Infrastructure\Repositories\TestTechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TestTechnicalLeaderController
{
    use ApiResponser;
    use ApiMessage;
    private $testTechnicalRepository;

    public function __construct(TestTechnicalRepository $testTechnicalRepository)
    {
        $this->testTechnicalRepository = $testTechnicalRepository;
    }

    public function __invoke(TestTechnicalLeaderRequest $request, int $testTechnicalId)
    {
        try {
            $testTechnicalId = new TestTechnicalId($testTechnicalId ?? null);
            $testTechnicalLeaderUseCase = new TestTechnicalLeaderUseCase($this->testTechnicalRepository);
            $testTechnical = new TestTechnicalResource($testTechnicalLeaderUseCase->__invoke($request, $testTechnicalId));
            return $this->successResponse(
                $testTechnical,
                'La calificación del lider de la prueba técnica fue actualizado exitosamente.',
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
