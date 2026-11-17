<?php

declare(strict_types=1);

namespace App\Application\DisabilityUseCase;

use App\Domain\Disability\Contracts\DisabilityContract;
use App\Domain\Disability\Disability;
use App\Domain\Disability\Values\DisabilityCIECodeId;
use App\Domain\Disability\Values\DisabilityDisabilityTypeId;
use App\Domain\Disability\Values\DisabilityEndDate;
use App\Domain\Disability\Values\DisabilityId;
use App\Domain\Disability\Values\DisabilityPersonId;
use App\Domain\Disability\Values\DisabilityReason;
use App\Domain\Disability\Values\DisabilityStartDate;
use App\Domain\Disability\Values\DisabilityStatus;
use App\Models\Disability as DisabilityModel;
use App\Http\Requests\Disability\DisabilityRejectedRequest;

final class DisabilityRejectedUseCase
{
    private $disabilityContract;

    public function __construct(DisabilityContract $disabilityContract)
    {
        $this->disabilityContract = $disabilityContract;
    }

    public function __invoke(DisabilityRejectedRequest $request, DisabilityId $disabilityId): Disability
    {
        return $this->disabilityContract->update(
            new Disability(
                new DisabilityId($disabilityId->value()),
                new DisabilityPersonId($request->integer('person_id')),
                new DisabilityDisabilityTypeId($request->integer('disability_type_id')),
                new DisabilityCIECodeId($request->integer('cie_code_id')),
                new DisabilityStartDate($request->input('start_date')),
                new DisabilityEndDate($request->input('end_date')),
                new DisabilityReason($request->input('reason')),
                new DisabilityStatus(DisabilityModel::REJECTED)
            ),
            $disabilityId
        );
    }
}
