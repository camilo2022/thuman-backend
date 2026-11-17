<?php

declare(strict_types=1);

namespace App\Application\WorkDayUseCase;

use App\Domain\WorkDay\Contracts\WorkDayContract;
use App\Domain\WorkDay\WorkDay;
use App\Domain\WorkDay\Values\WorkDayDescription;
use App\Domain\WorkDay\Values\WorkDayId;
use App\Domain\WorkDay\Values\WorkDayItemId;
use App\Domain\WorkDay\Values\WorkDayName;
use App\Domain\WorkDay\Values\WorkDaySettings;
use App\Http\Requests\WorkDay\WorkDayUpdateRequest;

final class WorkDayUpdateUseCase
{
    private $workDayContract;

    public function __construct(WorkDayContract $workDayContract)
    {
        $this->workDayContract = $workDayContract;
    }

    public function __invoke(WorkDayUpdateRequest $request, WorkDayId $workDayId): WorkDay
    {
        return $this->workDayContract->update(
            new WorkDay(
                new WorkDayId($workDayId->value()),
                new WorkDayItemId($request->integer('item_id')),
                new WorkDayName($request->input('name')),
                new WorkDayDescription($request->input('description')),
                new WorkDaySettings((object) [])
            ),
            $workDayId
        );
    }
}
