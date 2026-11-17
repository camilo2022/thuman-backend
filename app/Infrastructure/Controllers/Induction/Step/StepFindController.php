<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction\Step;

use App\Application\InductionUseCase\StepUseCase\StepFindUseCase;
use App\Domain\Induction\Step\Values\StepId;
use App\Http\Resources\Induction\Step\StepResource;
use App\Infrastructure\Repositories\StepRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class StepFindController
{
    use ApiResponser;
    use ApiMessage;
    private $stepRepository;

    public function __construct(StepRepository $stepRepository)
    {
        $this->stepRepository = $stepRepository;
    }

    public function __invoke(int $stepId)
    {
        try {
            $stepId = new StepId($stepId ?? null);
            $stepFindUseCase = new StepFindUseCase($this->stepRepository);
            $step = new StepResource($stepFindUseCase->__invoke($stepId));
            return $this->successResponse(
                $step,
                'El video fue encontrado exitosamente.',
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
