<?php

declare(strict_types=1);

namespace App\Application\LicenseUseCase;

use App\Domain\License\License;
use App\Domain\License\Contracts\LicenseContract;
use App\Domain\License\Values\LicenseId;

final class LicenseFindUseCase
{
    private $licenseContract;

    public function __construct(LicenseContract $licenseContract)
    {
        $this->licenseContract = $licenseContract;
    }

    public function __invoke(LicenseId $licenseId): ?License
    {
        return $this->licenseContract->find($licenseId);
    }
}
