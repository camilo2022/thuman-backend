<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewInitial;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase\InterviewInitialLeaderUseCase;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialLeaderRequest;
use App\Http\Resources\Vacant\Application\InterviewInitial\InterviewInitialResource;
use App\Infrastructure\Repositories\InterviewInitialRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewInitialLeaderController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewInitialRepository;

    public function __construct(InterviewInitialRepository $interviewInitialRepository)
    {
        $this->interviewInitialRepository = $interviewInitialRepository;
    }

    public function __invoke(InterviewInitialLeaderRequest $request, int $interviewInitialId)
    {
        try {
            $interviewInitialId = new InterviewInitialId($interviewInitialId ?? null);
            $interviewInitialLeaderUseCase = new InterviewInitialLeaderUseCase($this->interviewInitialRepository);
            $interviewInitial = new InterviewInitialResource($interviewInitialLeaderUseCase->__invoke($request, $interviewInitialId));
            return $this->successResponse(
                $interviewInitial,
                'La calificación del lider de la entrevista incial fue actualizado exitosamente.',
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
