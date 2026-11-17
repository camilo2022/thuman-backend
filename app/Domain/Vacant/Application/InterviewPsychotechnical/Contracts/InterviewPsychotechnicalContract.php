<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Contracts;

use App\Domain\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnical;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalId;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalAllRequest;
use App\Http\Requests\Vacant\Application\InterviewPsychotechnical\InterviewPsychotechnicalFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface InterviewPsychotechnicalContract
{
    public function all(string|null $search, int|null $perPage, InterviewPsychotechnicalAllRequest $request): LengthAwarePaginator;
    
    public function find(InterviewPsychotechnicalFindRequest $request, InterviewPsychotechnicalId $interviewPsychotechnicalId): ?InterviewPsychotechnical;

    public function store(InterviewPsychotechnical $interviewPsychotechnical): InterviewPsychotechnical;

    public function update(InterviewPsychotechnical $interviewPsychotechnical, InterviewPsychotechnicalId $interviewPsychotechnicalId): InterviewPsychotechnical;
}
