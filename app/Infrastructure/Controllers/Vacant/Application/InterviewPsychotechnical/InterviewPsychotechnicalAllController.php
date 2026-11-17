<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewPsychotechnical;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewPsychotechnicalUseCase\InterviewPsychotechnicalAllUseCase;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalAllRequest;
use App\Http\Resources\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalCollection;
use App\Infrastructure\Repositories\InterviewPsychotechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewPsychotechnicalAllController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewPsychotechnicalRepository;

    public function __construct(InterviewPsychotechnicalRepository $interviewPsychotechnicalRepository)
    {
        $this->interviewPsychotechnicalRepository = $interviewPsychotechnicalRepository;
    }

    public function __invoke(InterviewPsychotechnicalAllRequest $request)
    {
        try {
            $interviewPsychotechnicalAllUseCase = new InterviewPsychotechnicalAllUseCase($this->interviewPsychotechnicalRepository);
            $interviewPsychotechnicals = new InterviewPsychotechnicalCollection($interviewPsychotechnicalAllUseCase->__invoke($request));
            return $this->successResponse(
                $interviewPsychotechnicals,
                'Los agendamientos/resultados de las entrevistas psicotécnica consultadas exitosamente.',
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
