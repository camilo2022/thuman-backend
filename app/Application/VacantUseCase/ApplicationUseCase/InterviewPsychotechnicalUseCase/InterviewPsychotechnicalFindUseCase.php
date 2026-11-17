<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewPsychotechnicalUseCase;

use App\Domain\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnical;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Contracts\InterviewPsychotechnicalContract;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalFindRequest;

final class InterviewPsychotechnicalFindUseCase
{
    private $interviewPsychotechnicalContract;

    public function __construct(InterviewPsychotechnicalContract $interviewPsychotechnicalContract)
    {
        $this->interviewPsychotechnicalContract = $interviewPsychotechnicalContract;
    }

    public function __invoke(InterviewPsychotechnicalFindRequest $request, InterviewPsychotechnicalId $interviewPsychotechnicalId): ?InterviewPsychotechnical
    {
        return $this->interviewPsychotechnicalContract->find($request, $interviewPsychotechnicalId);
    }
}
