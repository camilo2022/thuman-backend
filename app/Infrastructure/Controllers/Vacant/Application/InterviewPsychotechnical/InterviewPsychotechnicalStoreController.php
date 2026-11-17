<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewPsychotechnical;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewPsychotechnicalUseCase\InterviewPsychotechnicalStoreUseCase;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalStoreRequest;
use App\Http\Resources\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalResource;
use App\Infrastructure\Repositories\InterviewPsychotechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewPsychotechnicalStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewPsychotechnicalRepository;

    public function __construct(InterviewPsychotechnicalRepository $interviewPsychotechnicalRepository)
    {
        $this->interviewPsychotechnicalRepository = $interviewPsychotechnicalRepository;
    }

    public function __invoke(InterviewPsychotechnicalStoreRequest $request)
    {
        try {
            $interviewPsychotechnicalStoreUseCase = new InterviewPsychotechnicalStoreUseCase($this->interviewPsychotechnicalRepository);
            $interviewPsychotechnical = new InterviewPsychotechnicalResource($interviewPsychotechnicalStoreUseCase->__invoke($request));
            return $this->successResponse(
                $interviewPsychotechnical,
                'El agendamiento de entrevista psicotécnica fue registrada exitosamente.',
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
