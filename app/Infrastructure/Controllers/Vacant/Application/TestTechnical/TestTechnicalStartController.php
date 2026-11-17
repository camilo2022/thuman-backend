<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\TestTechnical;

use App\Application\VacantUseCase\ApplicationUseCase\TestTechnicalUseCase\TestTechnicalStartUseCase;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalStartRequest;
use App\Http\Resources\Vacant\Application\TestTechnical\TestTechnicalResource;
use App\Infrastructure\Repositories\TestTechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TestTechnicalStartController
{
    use ApiResponser;
    use ApiMessage;
    private $testTechnicalRepository;

    public function __construct(TestTechnicalRepository $testTechnicalRepository)
    {
        $this->testTechnicalRepository = $testTechnicalRepository;
    }

    public function __invoke(TestTechnicalStartRequest $request, int $testTechnicalId)
    {
        try {
            $testTechnicalId = new TestTechnicalId($testTechnicalId ?? null);
            $testTechnicalStartUseCase = new TestTechnicalStartUseCase($this->testTechnicalRepository);
            $testTechnical = new TestTechnicalResource($testTechnicalStartUseCase->__invoke($request, $testTechnicalId));
            return $this->successResponse(
                $testTechnical,
                'La prueba técnica ha comenzado.',
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
