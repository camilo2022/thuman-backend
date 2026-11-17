<?php

declare(strict_types=1);

namespace App\Application\LicenseUseCase;

use App\Domain\License\Contracts\LicenseContract;
use App\Domain\License\License;
use App\Domain\License\Values\LicenseLicenseTypeId;
use App\Domain\License\Values\LicenseEndDate;
use App\Domain\License\Values\LicenseId;
use App\Domain\License\Values\LicensePersonId;
use App\Domain\License\Values\LicenseReason;
use App\Domain\License\Values\LicenseStartDate;
use App\Domain\License\Values\LicenseStatus;
use App\Models\License as LicenseModel;
use App\Http\Requests\License\LicenseApprovedRequest;

final class LicenseApprovedUseCase
{
    private $licenseContract;

    public function __construct(LicenseContract $licenseContract)
    {
        $this->licenseContract = $licenseContract;
    }

    public function __invoke(LicenseApprovedRequest $request, LicenseId $licenseId): License
    {
        return $this->licenseContract->update(
            new License(
                new LicenseId($licenseId->value()),
                new LicensePersonId($request->integer('person_id')),
                new LicenseLicenseTypeId($request->integer('license_type_id')),
                new LicenseStartDate($request->input('start_date')),
                new LicenseEndDate($request->input('end_date')),
                new LicenseReason($request->input('reason')),
                new LicenseStatus(LicenseModel::APPROVED)
            ),
            $licenseId
        );
    }
}
