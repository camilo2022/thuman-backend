<?php

declare(strict_types=1);

namespace App\Domain\LicenseType\Contracts;

use App\Domain\LicenseType\LicenseType;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Http\Requests\LicenseType\LicenseTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface LicenseTypeContract
{
    public function all(string|null $search, int|null $perPage, LicenseTypeAllRequest $request): LengthAwarePaginator;

    public function find(LicenseTypeId $licenseTypeId): ?LicenseType;

    public function store(LicenseType $licenseType): LicenseType;

    public function update(LicenseType $licenseType, LicenseTypeId $licenseTypeId): LicenseType;

    public function delete(LicenseTypeId $licenseTypeId): LicenseType;

    public function restore(LicenseTypeId $licenseTypeId): LicenseType;
}
