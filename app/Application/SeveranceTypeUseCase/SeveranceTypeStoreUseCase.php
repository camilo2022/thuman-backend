<?php

declare(strict_types=1);

namespace App\Application\SeveranceTypeUseCase;

use App\Domain\SeveranceType\Contracts\SeveranceTypeContract;
use App\Domain\SeveranceType\SeveranceType;
use App\Domain\SeveranceType\Values\SeveranceTypeDescription;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Domain\SeveranceType\Values\SeveranceTypeItemId;
use App\Domain\SeveranceType\Values\SeveranceTypeName;
use App\Domain\SeveranceType\Values\SeveranceTypeSettings;
use App\Http\Requests\SeveranceType\SeveranceTypeStoreRequest;

final class SeveranceTypeStoreUseCase
{
    private $severanceTypeContract;

    public function __construct(SeveranceTypeContract $severanceTypeContract)
    {
        $this->severanceTypeContract = $severanceTypeContract;
    }

    public function __invoke(SeveranceTypeStoreRequest $request): SeveranceType
    {
        return $this->severanceTypeContract->store(
            new SeveranceType(
                new SeveranceTypeId(null),
                new SeveranceTypeItemId($request->integer('item_id')),
                new SeveranceTypeName($request->input('name')),
                new SeveranceTypeDescription($request->input('description')),
                new SeveranceTypeSettings((object) $request->input('settings'))
            )
        );
    }
}
