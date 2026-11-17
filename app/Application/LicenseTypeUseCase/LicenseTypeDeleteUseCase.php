<?php

declare(strict_types=1);

namespace App\Application\LicenseTypeUseCase;

use App\Domain\LicenseType\LicenseType;
use App\Domain\LicenseType\Contracts\LicenseTypeContract;
use App\Domain\LicenseType\Values\LicenseTypeId;

final class LicenseTypeDeleteUseCase
{
    private $licenseTypeContract;

    public function __construct(LicenseTypeContract $licenseTypeContract)
    {
        $this->licenseTypeContract = $licenseTypeContract;
    }

    public function __invoke(LicenseTypeId $licenseTypeId): ?LicenseType
    {
        return $this->licenseTypeContract->delete($licenseTypeId);
    }
}
