<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Survey;

use App\Application\SurveyUseCase\SurveyFindUseCase;
use App\Domain\Survey\Values\SurveyId;
use App\Http\Resources\Survey\SurveyResource;
use App\Infrastructure\Repositories\SurveyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SurveyFindController
{
    use ApiResponser;
    use ApiMessage;
    private $surveyRepository;

    public function __construct(SurveyRepository $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    public function __invoke(int $surveyId)
    {
        try {
            $surveyId = new SurveyId($surveyId);
            $surveyFindUseCase = new SurveyFindUseCase($this->surveyRepository);
            $survey = new SurveyResource($surveyFindUseCase->__invoke($surveyId));
            return $this->successResponse(
                $survey,
                'La encuesta fue encontrada exitosamente.',
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
