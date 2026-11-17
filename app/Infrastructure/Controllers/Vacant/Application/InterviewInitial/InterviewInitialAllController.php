<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewInitial;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase\InterviewInitialAllUseCase;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialAllRequest;
use App\Http\Resources\Vacant\Application\InterviewInitial\InterviewInitialCollection;
use App\Infrastructure\Repositories\InterviewInitialRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewInitialAllController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewInitialRepository;

    public function __construct(InterviewInitialRepository $interviewInitialRepository)
    {
        $this->interviewInitialRepository = $interviewInitialRepository;
    }

    public function __invoke(InterviewInitialAllRequest $request)
    {
        try {
            $interviewInitialAllUseCase = new InterviewInitialAllUseCase($this->interviewInitialRepository);
            $interviewInitials = new InterviewInitialCollection($interviewInitialAllUseCase->__invoke($request));
            return $this->successResponse(
                $interviewInitials,
                'Los agendamientos/resultados de las entrevistas inciales consultadas exitosamente.',
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
