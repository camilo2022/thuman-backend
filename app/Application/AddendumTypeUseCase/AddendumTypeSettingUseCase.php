<?php

declare(strict_types=1);

namespace App\Application\AddendumTypeUseCase;

use App\Domain\AddendumType\Contracts\AddendumTypeContract;
use App\Domain\AddendumType\AddendumType;
use App\Domain\AddendumType\Values\AddendumTypeDescription;
use App\Domain\AddendumType\Values\AddendumTypeId;
use App\Domain\AddendumType\Values\AddendumTypeItemId;
use App\Domain\AddendumType\Values\AddendumTypeName;
use App\Domain\AddendumType\Values\AddendumTypeSettings;
use App\Http\Requests\AddendumType\AddendumTypeSettingRequest;

final class AddendumTypeSettingUseCase
{
    private $addendumTypeContract;

    public function __construct(AddendumTypeContract $addendumTypeContract)
    {
        $this->addendumTypeContract = $addendumTypeContract;
    }

    public function __invoke(AddendumTypeSettingRequest $request, AddendumTypeId $addendumTypeId): AddendumType
    {
        return $this->addendumTypeContract->update(
            new AddendumType(
                new AddendumTypeId($addendumTypeId->value()),
                new AddendumTypeItemId($request->integer('item_id')),
                new AddendumTypeName($request->input('name')),
                new AddendumTypeDescription($request->input('description')),
                new AddendumTypeSettings((object) $request->input('settings'))
            ),
            $addendumTypeId
        );
    }
}
