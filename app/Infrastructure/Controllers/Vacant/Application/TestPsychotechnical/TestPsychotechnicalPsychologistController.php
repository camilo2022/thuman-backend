<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\TestPsychotechnical;

use App\Application\VacantUseCase\ApplicationUseCase\TestPsychotechnicalUseCase\TestPsychotechnicalPsychologistUseCase;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalId;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalPsychologistRequest;
use App\Http\Resources\Vacant\Application\TestPsychotechnical\TestPsychotechnicalResource;
use App\Infrastructure\Repositories\TestPsychotechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TestPsychotechnicalPsychologistController
{
    use ApiResponser;
    use ApiMessage;
    private $testPsychotechnicalRepository;

    public function __construct(TestPsychotechnicalRepository $testPsychotechnicalRepository)
    {
        $this->testPsychotechnicalRepository = $testPsychotechnicalRepository;
    }

    public function __invoke(TestPsychotechnicalPsychologistRequest $request, int $testPsychotechnicalId)
    {
        try {
            $testPsychotechnicalId = new TestPsychotechnicalId($testPsychotechnicalId ?? null);
            $testPsychotechnicalPsychologistUseCase = new TestPsychotechnicalPsychologistUseCase($this->testPsychotechnicalRepository);
            $testPsychotechnical = new TestPsychotechnicalResource($testPsychotechnicalPsychologistUseCase->__invoke($request, $testPsychotechnicalId));
            return $this->successResponse(
                $testPsychotechnical,
                'La calificación del psicólogo de la prueba psicotécnica fue actualizado exitosamente.',
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
