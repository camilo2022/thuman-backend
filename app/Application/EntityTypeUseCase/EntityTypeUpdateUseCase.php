<?php

declare(strict_types=1);

namespace App\Application\EntityTypeUseCase;

use App\Domain\EntityType\Contracts\EntityTypeContract;
use App\Domain\EntityType\EntityType;
use App\Domain\EntityType\Values\EntityTypeDescription;
use App\Domain\EntityType\Values\EntityTypeId;
use App\Domain\EntityType\Values\EntityTypeItemId;
use App\Domain\EntityType\Values\EntityTypeName;
use App\Domain\EntityType\Values\EntityTypeSettings;
use App\Http\Requests\EntityType\EntityTypeUpdateRequest;

final class EntityTypeUpdateUseCase
{
    private $entityTypeContract;

    public function __construct(EntityTypeContract $entityTypeContract)
    {
        $this->entityTypeContract = $entityTypeContract;
    }

    public function __invoke(EntityTypeUpdateRequest $request, EntityTypeId $entityTypeId): EntityType
    {
        return $this->entityTypeContract->update(
            new EntityType(
                new EntityTypeId($entityTypeId->value()),
                new EntityTypeItemId($request->integer('item_id')),
                new EntityTypeName($request->input('name')),
                new EntityTypeDescription($request->input('description')),
                new EntityTypeSettings((object) $request->input('settings'))
            ),
            $entityTypeId
        );
    }
}
