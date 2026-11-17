<?php

declare(strict_types=1);

namespace App\Application\SurveyUseCase;

use App\Domain\Survey\Contracts\SurveyContract;
use App\Domain\Survey\Survey;
use App\Domain\Survey\Values\SurveyAnswers;
use App\Domain\Survey\Values\SurveyPersonId;
use App\Domain\Survey\Values\SurveyFormId;
use App\Domain\Survey\Values\SurveyId;
use App\Domain\Survey\Values\SurveyQuestions;
use App\Http\Requests\Survey\SurveyStoreRequest;

final class SurveyStoreUseCase
{
    private $surveyContract;

    public function __construct(SurveyContract $surveyContract)
    {
        $this->surveyContract = $surveyContract;
    }

    public function __invoke(SurveyStoreRequest $request): Survey
    {
        return $this->surveyContract->store(
            new Survey(
                new SurveyId(null),
                new SurveyFormId($request->input('form_id')),
                new SurveyQuestions($request->input('questions')),
                new SurveyAnswers($request->input('answers')),
                new SurveyPersonId($request->input('person_id'))
            )
        );
    }
}
