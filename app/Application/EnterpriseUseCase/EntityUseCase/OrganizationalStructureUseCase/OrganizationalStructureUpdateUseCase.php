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
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureUpdateRequest;
use App\Models\OrganizationalStructure as OrganizationalStructureModel;

final class OrganizationalStructureUpdateUseCase
{
    private $organizationalStructureContract;

    public function __construct(OrganizationalStructureContract $organizationalStructureContract)
    {
        $this->organizationalStructureContract = $organizationalStructureContract;
    }

    public function __invoke(OrganizationalStructureUpdateRequest $request, OrganizationalStructureId $organizationalStructureId): OrganizationalStructure
    {

        $entityId = $request->integer('entity_id');
        $positionId = $request->integer('position_id');
        $parentPositionId = $request->input('parent_position_id');

        if ($parentPositionId !== null) {
            $cycleExists = $this->organizationalStructureContract->existsPath(
                $entityId,
                $parentPositionId,
                $positionId,
                $organizationalStructureId->value()
            );

            if ($cycleExists) {
                throw new \Exception(
                    'No se puede modificar la relación porque generaría un ciclo en la estructura organizacional.'
                );
            }
        }

        return $this->organizationalStructureContract->update(
            new OrganizationalStructure(
                new OrganizationalStructureId($organizationalStructureId->value()),
                new OrganizationalStructureEntityId($entityId),
                new OrganizationalStructurePositionId($positionId),
                new OrganizationalStructureParentPositionId($parentPositionId),
                new OrganizationalStructureStartDate($request->input('start_date')),
                new OrganizationalStructureEndDate($request->input('end_date')),
                new OrganizationalStructureReason($request->input('reason')),
                new OrganizationalStructureStatus(OrganizationalStructureModel::PENDING)
            ),
            $organizationalStructureId
        );
    }
}
