<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\TestTechnical;

use App\Application\VacantUseCase\ApplicationUseCase\TestTechnicalUseCase\TestTechnicalFindUseCase;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalFindRequest;
use App\Http\Resources\Vacant\Application\TestTechnical\TestTechnicalResource;
use App\Infrastructure\Repositories\TestTechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TestTechnicalFindController
{
    use ApiResponser;
    use ApiMessage;
    private $testTechnicalRepository;

    public function __construct(TestTechnicalRepository $testTechnicalRepository)
    {
        $this->testTechnicalRepository = $testTechnicalRepository;
    }

    public function __invoke(TestTechnicalFindRequest $request, int|null $testTechnicalId = null)
    {
        try {
            $testTechnicalId = new TestTechnicalId($testTechnicalId ?? null);
            $testTechnicalFindUseCase = new TestTechnicalFindUseCase($this->testTechnicalRepository);
            $testTechnical = new TestTechnicalResource($testTechnicalFindUseCase->__invoke($request, $testTechnicalId));
            return $this->successResponse(
                $testTechnical,
                'El agendamiento/resultado de la prueba técnica fue encontrada exitosamente.',
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
