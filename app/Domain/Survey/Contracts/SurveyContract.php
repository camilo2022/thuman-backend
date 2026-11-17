<?php

declare(strict_types=1);

namespace App\Domain\Survey\Contracts;

use App\Domain\Survey\Survey;
use App\Domain\Survey\Values\SurveyId;
use App\Http\Requests\Survey\SurveyAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface SurveyContract
{
    public function all(string|null $search, int|null $perPage, SurveyAllRequest $request): LengthAwarePaginator;

    public function find(SurveyId $surveyId): ?Survey;

    public function store(Survey $survey): Survey;
}
