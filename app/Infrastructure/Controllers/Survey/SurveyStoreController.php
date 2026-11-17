<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Survey;

use App\Application\SurveyUseCase\SurveyStoreUseCase;
use App\Http\Requests\Survey\SurveyStoreRequest;
use App\Http\Resources\Survey\SurveyResource;
use App\Infrastructure\Repositories\SurveyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SurveyStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $surveyRepository;

    public function __construct(SurveyRepository $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    public function __invoke(SurveyStoreRequest $request)
    {
        try {
            $surveyStoreUseCase = new SurveyStoreUseCase($this->surveyRepository);
            $survey = new SurveyResource($surveyStoreUseCase->__invoke($request));
            return $this->successResponse(
                $survey,
                'La encuesta registrada exitosamente.',
                201
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
