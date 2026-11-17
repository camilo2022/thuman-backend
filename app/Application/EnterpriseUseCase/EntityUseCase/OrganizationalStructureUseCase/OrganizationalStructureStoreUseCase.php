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
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureStoreRequest;
use App\Models\OrganizationalStructure as OrganizationalStructureModel;

final class OrganizationalStructureStoreUseCase
{
    private $organizationalStructureContract;

    public function __construct(OrganizationalStructureContract $organizationalStructureContract)
    {
        $this->organizationalStructureContract = $organizationalStructureContract;
    }

    public function __invoke(OrganizationalStructureStoreRequest $request): OrganizationalStructure
    {

        $entityId = $request->integer('entity_id');
        $positionId = $request->integer('position_id');
        $parentPositionId = $request->input('parent_position_id');

        if ($parentPositionId !== null) {
            $cycleExists = $this->organizationalStructureContract->existsPath(
                $entityId,
                $parentPositionId,
                $positionId
            );

            if ($cycleExists) {
                throw new \Exception(
                    'No se puede crear la relación porque generaría un ciclo en la estructura organizacional.'
                );
            }
        }

        return $this->organizationalStructureContract->store(
            new OrganizationalStructure(
                new OrganizationalStructureId(null),
                new OrganizationalStructureEntityId($entityId),
                new OrganizationalStructurePositionId($positionId),
                new OrganizationalStructureParentPositionId($parentPositionId),
                new OrganizationalStructureStartDate($request->input('start_date')),
                new OrganizationalStructureEndDate($request->input('end_date')),
                new OrganizationalStructureReason($request->input('reason')),
                new OrganizationalStructureStatus(OrganizationalStructureModel::PENDING)
            )
        );
    }
}
