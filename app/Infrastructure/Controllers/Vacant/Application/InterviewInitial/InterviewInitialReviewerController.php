<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewInitial;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase\InterviewInitialReviewerUseCase;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialReviewerRequest;
use App\Http\Resources\Vacant\Application\InterviewInitial\InterviewInitialResource;
use App\Infrastructure\Repositories\InterviewInitialRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewInitialReviewerController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewInitialRepository;

    public function __construct(InterviewInitialRepository $interviewInitialRepository)
    {
        $this->interviewInitialRepository = $interviewInitialRepository;
    }

    public function __invoke(InterviewInitialReviewerRequest $request, int $interviewInitialId)
    {
        try {
            $interviewInitialId = new InterviewInitialId($interviewInitialId ?? null);
            $interviewInitialReviewerUseCase = new InterviewInitialReviewerUseCase($this->interviewInitialRepository);
            $interviewInitial = new InterviewInitialResource($interviewInitialReviewerUseCase->__invoke($request, $interviewInitialId));
            return $this->successResponse(
                $interviewInitial,
                'La calificación del revisor de de entrevista incial fue actualizado exitosamente.',
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
