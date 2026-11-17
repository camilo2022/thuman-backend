<?php

declare(strict_types=1);

namespace App\Application\WorkDayUseCase;

use App\Domain\WorkDay\Contracts\WorkDayContract;
use App\Http\Requests\WorkDay\WorkDayAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class WorkDayAllUseCase
{
    private $workDayContract;

    public function __construct(WorkDayContract $workDayContract)
    {
        $this->workDayContract = $workDayContract;
    }

    public function __invoke(WorkDayAllRequest $request): LengthAwarePaginator
    {
        return $this->workDayContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
