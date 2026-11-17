<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewPsychotechnical;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewPsychotechnicalUseCase\InterviewPsychotechnicalPsychologistUseCase;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalId;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalPsychologistRequest;
use App\Http\Resources\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalResource;
use App\Infrastructure\Repositories\InterviewPsychotechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewPsychotechnicalPsychologistController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewPsychotechnicalRepository;

    public function __construct(InterviewPsychotechnicalRepository $interviewPsychotechnicalRepository)
    {
        $this->interviewPsychotechnicalRepository = $interviewPsychotechnicalRepository;
    }

    public function __invoke(InterviewPsychotechnicalPsychologistRequest $request, int $interviewPsychotechnicalId)
    {
        try {
            $interviewPsychotechnicalId = new InterviewPsychotechnicalId($interviewPsychotechnicalId ?? null);
            $interviewPsychotechnicalPsychologistUseCase = new InterviewPsychotechnicalPsychologistUseCase($this->interviewPsychotechnicalRepository);
            $interviewPsychotechnical = new InterviewPsychotechnicalResource($interviewPsychotechnicalPsychologistUseCase->__invoke($request, $interviewPsychotechnicalId));
            return $this->successResponse(
                $interviewPsychotechnical,
                'La calificación del psicólogo de de entrevista incial fue actualizado exitosamente.',
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
