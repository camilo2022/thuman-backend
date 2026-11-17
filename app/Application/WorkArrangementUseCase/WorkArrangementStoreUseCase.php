<?php

declare(strict_types=1);

namespace App\Application\WorkArrangementUseCase;

use App\Domain\WorkArrangement\Contracts\WorkArrangementContract;
use App\Domain\WorkArrangement\WorkArrangement;
use App\Domain\WorkArrangement\Values\WorkArrangementDescription;
use App\Domain\WorkArrangement\Values\WorkArrangementId;
use App\Domain\WorkArrangement\Values\WorkArrangementItemId;
use App\Domain\WorkArrangement\Values\WorkArrangementName;
use App\Domain\WorkArrangement\Values\WorkArrangementSettings;
use App\Http\Requests\WorkArrangement\WorkArrangementStoreRequest;

final class WorkArrangementStoreUseCase
{
    private $workArrangementContract;

    public function __construct(WorkArrangementContract $workArrangementContract)
    {
        $this->workArrangementContract = $workArrangementContract;
    }

    public function __invoke(WorkArrangementStoreRequest $request): WorkArrangement
    {
        return $this->workArrangementContract->store(
            new WorkArrangement(
                new WorkArrangementId(null),
                new WorkArrangementItemId($request->integer('item_id')),
                new WorkArrangementName($request->input('name')),
                new WorkArrangementDescription($request->input('description')),
                new WorkArrangementSettings((object) [])
            )
        );
    }
}
