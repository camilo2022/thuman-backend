<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Induction\Step;

use App\Application\InductionUseCase\StepUseCase\StepStoreUseCase;
use App\Http\Requests\Induction\Step\StepStoreRequest;
use App\Http\Resources\Induction\Step\StepResource;
use App\Infrastructure\Repositories\StepRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class StepStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $stepRepository;

    public function __construct(StepRepository $stepRepository)
    {
        $this->stepRepository = $stepRepository;
    }

    public function __invoke(StepStoreRequest $request)
    {
        try {
            $stepStoreUseCase = new StepStoreUseCase($this->stepRepository);
            $step = new StepResource($stepStoreUseCase->__invoke($request));
            return $this->successResponse(
                $step,
                'El video fue registrado exitosamente.',
                201
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
