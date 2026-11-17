<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Contracts;

use App\Domain\Enterprise\Entity\Entity;
use App\Domain\Enterprise\Entity\Image;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Requests\Enterprise\Entity\EntityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface EntityContract
{
    public function all(string|null $search, int|null $perPage, EnterpriseId $enterpriseId, EntityAllRequest $request): LengthAwarePaginator;

    public function find(EntityId $entityId): ?Entity;

    public function store(Entity $entity): Entity;

    public function update(Entity $entity, EntityId $entityId): Entity;

    public function delete(EntityId $entityId): Entity;

    public function restore(EntityId $entityId): Entity;

    public function image(Image $logo): Image;
}
