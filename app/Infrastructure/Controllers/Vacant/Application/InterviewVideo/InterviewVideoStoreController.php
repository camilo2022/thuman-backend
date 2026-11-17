<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewVideo;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewVideoUseCase\InterviewVideoStoreUseCase;
use App\Http\Requests\Vacant\Application\InterviewVideo\InterviewVideoStoreRequest;
use App\Http\Resources\Vacant\Application\InterviewVideo\InterviewVideoResource;
use App\Infrastructure\Repositories\InterviewVideoRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewVideoStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewVideoRepository;

    public function __construct(InterviewVideoRepository $interviewVideoRepository)
    {
        $this->interviewVideoRepository = $interviewVideoRepository;
    }

    public function __invoke(InterviewVideoStoreRequest $request)
    {
        try {
            $interviewVideoStoreUseCase = new InterviewVideoStoreUseCase($this->interviewVideoRepository);
            $interviewVideo = new InterviewVideoResource($interviewVideoStoreUseCase->__invoke($request));
            return $this->successResponse(
                $interviewVideo,
                'La video entrevista fue registrada exitosamente.',
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
