<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewPsychotechnicalUseCase;

use App\Domain\Vacant\Application\InterviewPsychotechnical\Contracts\InterviewPsychotechnicalContract;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class InterviewPsychotechnicalAllUseCase
{
    private $interviewPsychotechnicalContract;

    public function __construct(InterviewPsychotechnicalContract $interviewPsychotechnicalContract)
    {
        $this->interviewPsychotechnicalContract = $interviewPsychotechnicalContract;
    }

    public function __invoke(InterviewPsychotechnicalAllRequest $request): LengthAwarePaginator
    {
        return $this->interviewPsychotechnicalContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
