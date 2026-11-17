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
use App\Http\Requests\TerminationType\TerminationTypeUpdateRequest;

final class TerminationTypeUpdateUseCase
{
    private $terminationTypeContract;

    public function __construct(TerminationTypeContract $terminationTypeContract)
    {
        $this->terminationTypeContract = $terminationTypeContract;
    }

    public function __invoke(TerminationTypeUpdateRequest $request, TerminationTypeId $terminationTypeId): TerminationType
    {
        return $this->terminationTypeContract->update(
            new TerminationType(
                new TerminationTypeId($terminationTypeId->value()),
                new TerminationTypeItemId($request->integer('item_id')),
                new TerminationTypeName($request->input('name')),
                new TerminationTypeDescription($request->input('description')),
                new TerminationTypeSettings((object) $request->input('settings'))
            ),
            $terminationTypeId
        );
    }
}
