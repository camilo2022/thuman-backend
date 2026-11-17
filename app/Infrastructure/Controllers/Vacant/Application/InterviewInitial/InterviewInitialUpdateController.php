<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewInitial;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase\InterviewInitialUpdateUseCase;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialUpdateRequest;
use App\Http\Resources\Vacant\Application\InterviewInitial\InterviewInitialResource;
use App\Infrastructure\Repositories\InterviewInitialRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewInitialUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewInitialRepository;

    public function __construct(InterviewInitialRepository $interviewInitialRepository)
    {
        $this->interviewInitialRepository = $interviewInitialRepository;
    }

    public function __invoke(InterviewInitialUpdateRequest $request, int $interviewInitialId)
    {
        try {
            $interviewInitialId = new InterviewInitialId($interviewInitialId ?? null);
            $interviewInitialUpdateUseCase = new InterviewInitialUpdateUseCase($this->interviewInitialRepository);
            $interviewInitial = new InterviewInitialResource($interviewInitialUpdateUseCase->__invoke($request, $interviewInitialId));
            return $this->successResponse(
                $interviewInitial,
                'El agendamiento de entrevista incial fue actualizado exitosamente.',
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
