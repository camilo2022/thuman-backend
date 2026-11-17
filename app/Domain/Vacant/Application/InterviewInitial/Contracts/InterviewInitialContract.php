<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Contracts;

use App\Domain\Vacant\Application\InterviewInitial\InterviewInitial;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialAllRequest;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface InterviewInitialContract
{
    public function all(string|null $search, int|null $perPage, InterviewInitialAllRequest $request): LengthAwarePaginator;

    public function find(InterviewInitialFindRequest $request, InterviewInitialId $interviewInitialId): ?InterviewInitial;

    public function store(InterviewInitial $interviewInitial): InterviewInitial;

    public function update(InterviewInitial $interviewInitial, InterviewInitialId $interviewInitialId): InterviewInitial;
}
