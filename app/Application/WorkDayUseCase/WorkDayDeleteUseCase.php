<?php

declare(strict_types=1);

namespace App\Application\WorkDayUseCase;

use App\Domain\WorkDay\WorkDay;
use App\Domain\WorkDay\Contracts\WorkDayContract;
use App\Domain\WorkDay\Values\WorkDayId;

final class WorkDayDeleteUseCase
{
    private $workDayContract;

    public function __construct(WorkDayContract $workDayContract)
    {
        $this->workDayContract = $workDayContract;
    }

    public function __invoke(WorkDayId $workDayId): ?WorkDay
    {
        return $this->workDayContract->delete($workDayId);
    }
}
