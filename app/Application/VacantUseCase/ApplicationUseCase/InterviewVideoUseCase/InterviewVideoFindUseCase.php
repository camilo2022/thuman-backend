<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewVideoUseCase;

use App\Domain\Vacant\Application\InterviewVideo\InterviewVideo;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoId;
use App\Domain\Vacant\Application\InterviewVideo\Contracts\InterviewVideoContract;
use App\Http\Requests\Vacant\Application\InterviewVideo\InterviewVideoFindRequest;

final class InterviewVideoFindUseCase
{
    private $interviewVideoContract;

    public function __construct(InterviewVideoContract $interviewVideoContract)
    {
        $this->interviewVideoContract = $interviewVideoContract;
    }

    public function __invoke(InterviewVideoFindRequest $request, InterviewVideoId $interviewVideoId): ?InterviewVideo
    {
        return $this->interviewVideoContract->find($request, $interviewVideoId);
    }
}
