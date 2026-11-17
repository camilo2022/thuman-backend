<?php

declare(strict_types=1);

namespace App\Application\TerminationTypeUseCase;

use App\Domain\TerminationType\Contracts\TerminationTypeContract;
use App\Domain\TerminationType\TerminationType;
use App\Domain\TerminationType\Values\TerminationTypeDescription;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Domain\TerminationType\Values\TerminationTypeItemId;
use App\Domain\TerminationType\Values\TerminationTypeName;
use App\Domain\TerminationType\Values\TerminationTypeSettings;
use App\Http\Requests\TerminationType\TerminationTypeStoreRequest;

final class TerminationTypeStoreUseCase
{
    private $terminationTypeContract;

    public function __construct(TerminationTypeContract $terminationTypeContract)
    {
        $this->terminationTypeContract = $terminationTypeContract;
    }

    public function __invoke(TerminationTypeStoreRequest $request): TerminationType
    {
        return $this->terminationTypeContract->store(
            new TerminationType(
                new TerminationTypeId(null),
                new TerminationTypeItemId($request->integer('item_id')),
                new TerminationTypeName($request->input('name')),
                new TerminationTypeDescription($request->input('description')),
                new TerminationTypeSettings((object) $request->input('settings'))
            )
        );
    }
}
