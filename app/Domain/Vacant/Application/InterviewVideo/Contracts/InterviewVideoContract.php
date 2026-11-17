<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewVideo\Contracts;

use App\Domain\Vacant\Application\InterviewVideo\InterviewVideo;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoId;
use App\Http\Requests\Vacant\Application\InterviewVideo\InterviewVideoFindRequest;

interface InterviewVideoContract
{
    public function find(InterviewVideoFindRequest $request, InterviewVideoId $interviewVideoId): ?InterviewVideo;

    public function store(InterviewVideo $interviewVideo): InterviewVideo;

    public function update(InterviewVideo $interviewVideo, InterviewVideoId $interviewVideoId): InterviewVideo;
}
