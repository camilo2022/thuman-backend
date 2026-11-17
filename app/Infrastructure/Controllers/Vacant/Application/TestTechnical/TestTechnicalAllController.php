<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\TestTechnical;

use App\Application\VacantUseCase\ApplicationUseCase\TestTechnicalUseCase\TestTechnicalAllUseCase;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalAllRequest;
use App\Http\Resources\Vacant\Application\TestTechnical\TestTechnicalCollection;
use App\Infrastructure\Repositories\TestTechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TestTechnicalAllController
{
    use ApiResponser;
    use ApiMessage;
    private $testTechnicalRepository;

    public function __construct(TestTechnicalRepository $testTechnicalRepository)
    {
        $this->testTechnicalRepository = $testTechnicalRepository;
    }

    public function __invoke(TestTechnicalAllRequest $request)
    {
        try {
            $testTechnicalAllUseCase = new TestTechnicalAllUseCase($this->testTechnicalRepository);
            $testTechnicals = new TestTechnicalCollection($testTechnicalAllUseCase->__invoke($request));
            return $this->successResponse(
                $testTechnicals,
                'Los agendamientos/resultados de las pruebas técnicas consultadas exitosamente.',
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
