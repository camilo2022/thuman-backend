<?php

declare(strict_types=1);

namespace App\Application\ProgressUseCase;

use App\Domain\Progress\Contracts\ProgressContract;
use App\Domain\Progress\Progress;
use App\Domain\Progress\Values\ProgressAnswers;
use App\Domain\Progress\Values\ProgressId;
use App\Domain\Progress\Values\ProgressEndAt;
use App\Domain\Progress\Values\ProgressPersonId;
use App\Domain\Progress\Values\ProgressQuestions;
use App\Domain\Progress\Values\ProgressResult;
use App\Domain\Progress\Values\ProgressStartAt;
use App\Domain\Progress\Values\ProgressStatus;
use App\Domain\Progress\Values\ProgressStepId;
use App\Http\Requests\Progress\ProgressStartRequest;
use App\Models\Progress as ProgressModel;
use Carbon\Carbon;

final class ProgressStartUseCase
{
    private $progressContract;

    public function __construct(ProgressContract $progressContract)
    {
        $this->progressContract = $progressContract;
    }

    public function __invoke(ProgressStartRequest $request): Progress
    {
        return $this->progressContract->store(
            new Progress(
                new ProgressId(null),
                new ProgressPersonId($request->integer('person_id')),
                new ProgressStepId($request->integer('step_id')),
                new ProgressQuestions($request->input('questions')),
                new ProgressAnswers($request->input('answers')),
                new ProgressStartAt(Carbon::now()->format('Y-m-d H:i:s')),
                new ProgressEndAt($request->input('end_at')),
                new ProgressStatus(ProgressModel::INPROGRESS),
                new ProgressResult(ProgressModel::PENDING)
            )
        );
    }
}
