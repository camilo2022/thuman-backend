<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction\Step;

use App\Application\InductionUseCase\StepUseCase\StepAllUseCase;
use App\Domain\Induction\Values\InductionId;
use App\Http\Requests\Induction\Step\StepAllRequest;
use App\Http\Resources\Induction\Step\StepCollection;
use App\Infrastructure\Repositories\StepRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class StepAllController
{
    use ApiResponser;
    use ApiMessage;
    private $stepRepository;

    public function __construct(StepRepository $stepRepository)
    {
        $this->stepRepository = $stepRepository;
    }

    public function __invoke(StepAllRequest $request, int $inductionId)
    {
        try {
            $inductionId = new InductionId($inductionId);
            $stepAllUseCase = new StepAllUseCase($this->stepRepository);
            $steps = new StepCollection($stepAllUseCase->__invoke($request, $inductionId));
            return $this->successResponse(
                $steps,
                'Los videos fueron consultados exitosamente.',
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
