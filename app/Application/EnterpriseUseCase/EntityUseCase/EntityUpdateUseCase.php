<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase;

use App\Domain\Enterprise\Entity\Contracts\EntityContract;
use App\Domain\Enterprise\Entity\Entity;
use App\Domain\Enterprise\Entity\Values\EntityAboutUs;
use App\Domain\Enterprise\Entity\Values\EntityEntityTypeId;
use App\Domain\Enterprise\Entity\Values\EntityCountryId;
use App\Domain\Enterprise\Entity\Values\EntityDocumentNumber;
use App\Domain\Enterprise\Entity\Values\EntityDocumentTypeId;
use App\Domain\Enterprise\Entity\Values\EntityEnterpriseId;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Domain\Enterprise\Entity\Values\EntityMission;
use App\Domain\Enterprise\Entity\Values\EntityName;
use App\Domain\Enterprise\Entity\Values\EntityPersonId;
use App\Domain\Enterprise\Entity\Values\EntitySettings;
use App\Domain\Enterprise\Entity\Values\EntityVision;
use App\Http\Requests\Enterprise\Entity\EntityUpdateRequest;

final class EntityUpdateUseCase
{
    private $entityContract;

    public function __construct(EntityContract $entityContract)
    {
        $this->entityContract = $entityContract;
    }

    public function __invoke(EntityUpdateRequest $request, EntityId $entityId): Entity
    {
        return $this->entityContract->update(
            new Entity(
                new EntityId($entityId->value()),
                new EntityEnterpriseId($request->input('enterprise_id')),
                new EntityEntityTypeId($request->input('entity_type_id')),
                new EntityName($request->input('name')),
                new EntityCountryId($request->input('country_id')),
                new EntityDocumentTypeId($request->input('document_type_id')),
                new EntityDocumentNumber($request->input('document_number')),
                new EntityPersonId($request->input('person_id')),
                new EntityAboutUs(null),
                new EntityMission(null),
                new EntityVision(null),
                new EntitySettings($request->input('settings'))
            ),
            $entityId
        );
    }
}
