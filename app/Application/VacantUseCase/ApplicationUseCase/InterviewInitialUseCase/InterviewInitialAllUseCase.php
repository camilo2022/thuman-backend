<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\InterviewInitialUseCase;

use App\Domain\Vacant\Application\InterviewInitial\Contracts\InterviewInitialContract;
use App\Http\Requests\Vacant\Application\InterviewInitial\InterviewInitialAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class InterviewInitialAllUseCase
{
    private $interviewInitialContract;

    public function __construct(InterviewInitialContract $interviewInitialContract)
    {
        $this->interviewInitialContract = $interviewInitialContract;
    }

    public function __invoke(InterviewInitialAllRequest $request): LengthAwarePaginator
    {
        return $this->interviewInitialContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
