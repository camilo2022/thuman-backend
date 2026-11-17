<?php

declare(strict_types=1);

namespace App\Application\AbsenceTypeUseCase;

use App\Domain\AbsenceType\Contracts\AbsenceTypeContract;
use App\Domain\AbsenceType\AbsenceType;
use App\Domain\AbsenceType\Values\AbsenceTypeDescription;
use App\Domain\AbsenceType\Values\AbsenceTypeId;
use App\Domain\AbsenceType\Values\AbsenceTypeItemId;
use App\Domain\AbsenceType\Values\AbsenceTypeName;
use App\Domain\AbsenceType\Values\AbsenceTypeSettings;
use App\Http\Requests\AbsenceType\AbsenceTypeStoreRequest;

final class AbsenceTypeStoreUseCase
{
    private $absenceTypeContract;

    public function __construct(AbsenceTypeContract $absenceTypeContract)
    {
        $this->absenceTypeContract = $absenceTypeContract;
    }

    public function __invoke(AbsenceTypeStoreRequest $request): AbsenceType
    {
        return $this->absenceTypeContract->store(
            new AbsenceType(
                new AbsenceTypeId(null),
                new AbsenceTypeItemId($request->integer('item_id')),
                new AbsenceTypeName($request->input('name')),
                new AbsenceTypeDescription($request->input('description')),
                new AbsenceTypeSettings((object) $request->input('settings'))
            )
        );
    }
}
