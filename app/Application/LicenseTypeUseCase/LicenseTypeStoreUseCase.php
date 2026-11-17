<?php

declare(strict_types=1);

namespace App\Application\LicenseTypeUseCase;

use App\Domain\LicenseType\Contracts\LicenseTypeContract;
use App\Domain\LicenseType\LicenseType;
use App\Domain\LicenseType\Values\LicenseTypeDescription;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Domain\LicenseType\Values\LicenseTypeItemId;
use App\Domain\LicenseType\Values\LicenseTypeName;
use App\Domain\LicenseType\Values\LicenseTypeSettings;
use App\Http\Requests\LicenseType\LicenseTypeStoreRequest;

final class LicenseTypeStoreUseCase
{
    private $licenseTypeContract;

    public function __construct(LicenseTypeContract $licenseTypeContract)
    {
        $this->licenseTypeContract = $licenseTypeContract;
    }

    public function __invoke(LicenseTypeStoreRequest $request): LicenseType
    {
        return $this->licenseTypeContract->store(
            new LicenseType(
                new LicenseTypeId(null),
                new LicenseTypeItemId($request->integer('item_id')),
                new LicenseTypeName($request->input('name')),
                new LicenseTypeDescription($request->input('description')),
                new LicenseTypeSettings((object) $request->input('settings'))
            )
        );
    }
}
