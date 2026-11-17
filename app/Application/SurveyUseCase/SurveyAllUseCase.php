<?php

declare(strict_types=1);

namespace App\Application\SurveyUseCase;

use App\Domain\Survey\Contracts\SurveyContract;
use App\Http\Requests\Survey\SurveyAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SurveyAllUseCase
{
    private $surveyContract;

    public function __construct(SurveyContract $surveyContract)
    {
        $this->surveyContract = $surveyContract;
    }

    public function __invoke(SurveyAllRequest $request): LengthAwarePaginator
    {
        return $this->surveyContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
