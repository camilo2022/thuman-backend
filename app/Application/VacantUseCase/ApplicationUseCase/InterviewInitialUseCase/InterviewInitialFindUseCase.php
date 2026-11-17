<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase;

use App\Domain\Vacant\Application\InterviewInitial\InterviewInitial;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Domain\Vacant\Application\InterviewInitial\Contracts\InterviewInitialContract;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialFindRequest;

final class InterviewInitialFindUseCase
{
    private $interviewInitialContract;

    public function __construct(InterviewInitialContract $interviewInitialContract)
    {
        $this->interviewInitialContract = $interviewInitialContract;
    }

    public function __invoke(InterviewInitialFindRequest $request, InterviewInitialId $interviewInitialId): ?InterviewInitial
    {
        return $this->interviewInitialContract->find($request, $interviewInitialId);
    }
}
