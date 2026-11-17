<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\TestPsychotechnical;

use App\Application\VacantUseCase\ApplicationUseCase\TestPsychotechnicalUseCase\TestPsychotechnicalStoreUseCase;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalStoreRequest;
use App\Http\Resources\Vacant\Application\TestPsychotechnical\TestPsychotechnicalResource;
use App\Infrastructure\Repositories\TestPsychotechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TestPsychotechnicalStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $testPsychotechnicalRepository;

    public function __construct(TestPsychotechnicalRepository $testPsychotechnicalRepository)
    {
        $this->testPsychotechnicalRepository = $testPsychotechnicalRepository;
    }

    public function __invoke(TestPsychotechnicalStoreRequest $request)
    {
        try {
            $testPsychotechnicalStoreUseCase = new TestPsychotechnicalStoreUseCase($this->testPsychotechnicalRepository);
            $testPsychotechnical = new TestPsychotechnicalResource($testPsychotechnicalStoreUseCase->__invoke($request));
            return $this->successResponse(
                $testPsychotechnical,
                'El agendamiento de la prueba psicotécnica fue registrada exitosamente.',
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
