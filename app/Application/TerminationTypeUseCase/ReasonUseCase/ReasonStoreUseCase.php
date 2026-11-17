<?php

declare(strict_types=1);

namespace App\Application\TerminationTypeUseCase\ReasonUseCase;

use App\Domain\TerminationType\Reason\Contracts\ReasonContract;
use App\Domain\TerminationType\Reason\Reason;
use App\Domain\TerminationType\Reason\Values\ReasonDescription;
use App\Domain\TerminationType\Reason\Values\ReasonId;
use App\Domain\TerminationType\Reason\Values\ReasonItemId;
use App\Domain\TerminationType\Reason\Values\ReasonName;
use App\Domain\TerminationType\Reason\Values\ReasonTerminationTypeId;
use App\Domain\TerminationType\Reason\Values\ReasonSettings;
use App\Http\Requests\TerminationType\Reason\ReasonStoreRequest;

final class ReasonStoreUseCase
{
    private $reasonContract;

    public function __construct(ReasonContract $reasonContract)
    {
        $this->reasonContract = $reasonContract;
    }

    public function __invoke(ReasonStoreRequest $request): Reason
    {
        return $this->reasonContract->store(
            new Reason(
                new ReasonId(null),
                new ReasonTerminationTypeId($request->integer('termination_type_id')),
                new ReasonItemId($request->integer('item_id')),
                new ReasonName($request->input('name')),
                new ReasonDescription($request->input('description')),
                new ReasonSettings((object) $request->input('settings'))
            )
        );
    }
}
