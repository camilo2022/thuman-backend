<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\InterviewPsychotechnical;

use App\Application\VacantUseCase\ApplicationUseCase\InterviewPsychotechnicalUseCase\InterviewPsychotechnicalFindUseCase;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalId;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalFindRequest;
use App\Http\Resources\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalResource;
use App\Infrastructure\Repositories\InterviewPsychotechnicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class InterviewPsychotechnicalFindController
{
    use ApiResponser;
    use ApiMessage;
    private $interviewPsychotechnicalRepository;

    public function __construct(InterviewPsychotechnicalRepository $interviewPsychotechnicalRepository)
    {
        $this->interviewPsychotechnicalRepository = $interviewPsychotechnicalRepository;
    }

    public function __invoke(InterviewPsychotechnicalFindRequest $request, int|null $interviewPsychotechnicalId = null)
    {
        try {
            $interviewPsychotechnicalId = new InterviewPsychotechnicalId($interviewPsychotechnicalId ?? null);
            $interviewPsychotechnicalFindUseCase = new InterviewPsychotechnicalFindUseCase($this->interviewPsychotechnicalRepository);
            $interviewPsychotechnical = new InterviewPsychotechnicalResource($interviewPsychotechnicalFindUseCase->__invoke($request, $interviewPsychotechnicalId));
            return $this->successResponse(
                $interviewPsychotechnical,
                'El agendamiento/resultado de entrevista psicotécnica fue encontrada exitosamente.',
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
