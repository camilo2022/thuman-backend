<?php

declare(strict_types=1);

namespace App\Application\VacationTypeUseCase;

use App\Domain\VacationType\Contracts\VacationTypeContract;
use App\Domain\VacationType\VacationType;
use App\Domain\VacationType\Values\VacationTypeDescription;
use App\Domain\VacationType\Values\VacationTypeId;
use App\Domain\VacationType\Values\VacationTypeItemId;
use App\Domain\VacationType\Values\VacationTypeName;
use App\Domain\VacationType\Values\VacationTypeSettings;
use App\Http\Requests\VacationType\VacationTypeUpdateRequest;

final class VacationTypeUpdateUseCase
{
    private $vacationTypeContract;

    public function __construct(VacationTypeContract $vacationTypeContract)
    {
        $this->vacationTypeContract = $vacationTypeContract;
    }

    public function __invoke(VacationTypeUpdateRequest $request, VacationTypeId $vacationTypeId): VacationType
    {
        return $this->vacationTypeContract->update(
            new VacationType(
                new VacationTypeId($vacationTypeId->value()),
                new VacationTypeItemId($request->integer('item_id')),
                new VacationTypeName($request->input('name')),
                new VacationTypeDescription($request->input('description')),
                new VacationTypeSettings((object) $request->input('settings'))
            ),
            $vacationTypeId
        );
    }
}
