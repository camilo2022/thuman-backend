<?php

declare(strict_types=1);

namespace App\Application\DisabilityTypeUseCase;

use App\Domain\DisabilityType\Contracts\DisabilityTypeContract;
use App\Domain\DisabilityType\DisabilityType;
use App\Domain\DisabilityType\Values\DisabilityTypeDescription;
use App\Domain\DisabilityType\Values\DisabilityTypeId;
use App\Domain\DisabilityType\Values\DisabilityTypeItemId;
use App\Domain\DisabilityType\Values\DisabilityTypeName;
use App\Domain\DisabilityType\Values\DisabilityTypeSettings;
use App\Http\Requests\DisabilityType\DisabilityTypeSettingRequest;

final class DisabilityTypeSettingUseCase
{
    private $disabilityTypeContract;

    public function __construct(DisabilityTypeContract $disabilityTypeContract)
    {
        $this->disabilityTypeContract = $disabilityTypeContract;
    }

    public function __invoke(DisabilityTypeSettingRequest $request, DisabilityTypeId $disabilityTypeId): DisabilityType
    {
        return $this->disabilityTypeContract->update(
            new DisabilityType(
                new DisabilityTypeId($disabilityTypeId->value()),
                new DisabilityTypeItemId($request->integer('item_id')),
                new DisabilityTypeName($request->input('name')),
                new DisabilityTypeDescription($request->input('description')),
                new DisabilityTypeSettings((object) $request->input('settings'))
            ),
            $disabilityTypeId
        );
    }
}
