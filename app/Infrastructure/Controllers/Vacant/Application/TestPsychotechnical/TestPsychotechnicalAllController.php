<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\TestPsychotechnical;

use App\Application\VacantUseCase\ApplicationUseCase\TestPsychotechnicalUseCase\TestPsychotechnicalAllUseCase;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalAllRequest;
use App\Http\Resources\Vacant\Application\TestPsychotechnical\TestPsychotechnicalCollection;
use App\Infrastructure\Repositories\TestPsychotechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TestPsychotechnicalAllController
{
    use ApiResponser;
    use ApiMessage;
    private $testPsychotechnicalRepository;

    public function __construct(TestPsychotechnicalRepository $testPsychotechnicalRepository)
    {
        $this->testPsychotechnicalRepository = $testPsychotechnicalRepository;
    }

    public function __invoke(TestPsychotechnicalAllRequest $request)
    {
        try {
            $testPsychotechnicalAllUseCase = new TestPsychotechnicalAllUseCase($this->testPsychotechnicalRepository);
            $testPsychotechnicals = new TestPsychotechnicalCollection($testPsychotechnicalAllUseCase->__invoke($request));
            return $this->successResponse(
                $testPsychotechnicals,
                'Los agendamientos/resultados de las pruebas psicotécnicas consultadas exitosamente.',
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
