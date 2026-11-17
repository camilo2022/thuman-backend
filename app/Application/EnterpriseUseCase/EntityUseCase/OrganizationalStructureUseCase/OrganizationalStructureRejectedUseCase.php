<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase;

use App\Domain\OrganizationalStructure\Contracts\OrganizationalStructureContract;
use App\Domain\OrganizationalStructure\OrganizationalStructure;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureEndDate;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureEntityId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructurePositionId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureParentPositionId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureReason;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureStartDate;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureStatus;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureRejectedRequest;
use App\Models\OrganizationalStructure as OrganizationalStructureModel;

final class OrganizationalStructureRejectedUseCase
{
    private $organizationalStructureContract;

    public function __construct(OrganizationalStructureContract $organizationalStructureContract)
    {
        $this->organizationalStructureContract = $organizationalStructureContract;
    }

    public function __invoke(OrganizationalStructureRejectedRequest $request, OrganizationalStructureId $organizationalStructureId): OrganizationalStructure
    {
        return $this->organizationalStructureContract->updateStatus(
            $organizationalStructureId,
            new OrganizationalStructureStatus(OrganizationalStructureModel::REJECTED)

        );
    }
}
