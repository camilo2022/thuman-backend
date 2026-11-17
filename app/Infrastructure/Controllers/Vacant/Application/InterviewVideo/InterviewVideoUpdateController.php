<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewVideo;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewVideoUseCase\InterviewVideoUpdateUseCase;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoId;
use App\Http\Requests\Vacant\Application\InterviewVideo\InterviewVideoUpdateRequest;
use App\Http\Resources\Vacant\Application\InterviewVideo\InterviewVideoResource;
use App\Infrastructure\Repositories\InterviewVideoRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewVideoUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewVideoRepository;

    public function __construct(InterviewVideoRepository $interviewVideoRepository)
    {
        $this->interviewVideoRepository = $interviewVideoRepository;
    }

    public function __invoke(InterviewVideoUpdateRequest $request, int $interviewVideoId)
    {
        try {
            $interviewVideoId = new InterviewVideoId($interviewVideoId ?? null);
            $interviewVideoUpdateUseCase = new InterviewVideoUpdateUseCase($this->interviewVideoRepository);
            $interviewVideo = new InterviewVideoResource($interviewVideoUpdateUseCase->__invoke($request, $interviewVideoId));
            return $this->successResponse(
                $interviewVideo,
                'La video entrevista fue actualizado exitosamente.',
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
