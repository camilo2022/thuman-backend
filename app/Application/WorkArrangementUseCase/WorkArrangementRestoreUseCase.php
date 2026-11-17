<?php

declare(strict_types=1);

namespace App\Application\WorkArrangementUseCase;

use App\Domain\WorkArrangement\WorkArrangement;
use App\Domain\WorkArrangement\Contracts\WorkArrangementContract;
use App\Domain\WorkArrangement\Values\WorkArrangementId;

final class WorkArrangementRestoreUseCase
{
    private $workArrangementContract;

    public function __construct(WorkArrangementContract $workArrangementContract)
    {
        $this->workArrangementContract = $workArrangementContract;
    }

    public function __invoke(WorkArrangementId $workArrangementId): ?WorkArrangement
    {
        return $this->workArrangementContract->restore($workArrangementId);
    }
}
