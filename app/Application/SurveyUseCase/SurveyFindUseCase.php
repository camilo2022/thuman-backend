<?php

declare(strict_types=1);

namespace App\Application\SurveyUseCase;

use App\Domain\Survey\Survey;
use App\Domain\Survey\Contracts\SurveyContract;
use App\Domain\Survey\Values\SurveyId;

final class SurveyFindUseCase
{
    private $surveyContract;

    public function __construct(SurveyContract $surveyContract)
    {
        $this->surveyContract = $surveyContract;
    }

    public function __invoke(SurveyId $surveyId): ?Survey
    {
        return $this->surveyContract->find($surveyId);
    }
}
