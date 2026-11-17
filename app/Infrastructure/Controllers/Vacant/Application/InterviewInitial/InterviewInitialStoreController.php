<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewInitial;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase\InterviewInitialStoreUseCase;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialStoreRequest;
use App\Http\Resources\Vacant\Application\InterviewInitial\InterviewInitialResource;
use App\Infrastructure\Repositories\InterviewInitialRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewInitialStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewInitialRepository;

    public function __construct(InterviewInitialRepository $interviewInitialRepository)
    {
        $this->interviewInitialRepository = $interviewInitialRepository;
    }

    public function __invoke(InterviewInitialStoreRequest $request)
    {
        try {
            $interviewInitialStoreUseCase = new InterviewInitialStoreUseCase($this->interviewInitialRepository);
            $interviewInitial = new InterviewInitialResource($interviewInitialStoreUseCase->__invoke($request));
            return $this->successResponse(
                $interviewInitial,
                'El agendamiento de entrevista incial fue registrada exitosamente.',
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
