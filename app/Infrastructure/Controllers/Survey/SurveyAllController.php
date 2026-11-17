<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Survey;

use App\Application\SurveyUseCase\SurveyAllUseCase;
use App\Http\Requests\Survey\SurveyAllRequest;
use App\Http\Resources\Survey\SurveyCollection;
use App\Infrastructure\Repositories\SurveyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SurveyAllController
{
    use ApiResponser;
    use ApiMessage;
    private $surveyRepository;

    public function __construct(SurveyRepository $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    public function __invoke(SurveyAllRequest $request)
    {
        try {
            $surveyAllUseCase = new SurveyAllUseCase($this->surveyRepository);
            $surveys = new SurveyCollection($surveyAllUseCase->__invoke($request));
            return $this->successResponse(
                $surveys,
                'Las encuestas consultadas exitosamente.',
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
