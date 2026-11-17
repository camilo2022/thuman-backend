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
use App\Http\Requests\DisabilityType\DisabilityTypeStoreRequest;

final class DisabilityTypeStoreUseCase
{
    private $disabilityTypeContract;

    public function __construct(DisabilityTypeContract $disabilityTypeContract)
    {
        $this->disabilityTypeContract = $disabilityTypeContract;
    }

    public function __invoke(DisabilityTypeStoreRequest $request): DisabilityType
    {
        return $this->disabilityTypeContract->store(
            new DisabilityType(
                new DisabilityTypeId(null),
                new DisabilityTypeItemId($request->integer('item_id')),
                new DisabilityTypeName($request->input('name')),
                new DisabilityTypeDescription($request->input('description')),
                new DisabilityTypeSettings((object) $request->input('settings'))
            )
        );
    }
}
