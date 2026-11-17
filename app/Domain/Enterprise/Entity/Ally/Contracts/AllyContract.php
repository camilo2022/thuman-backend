<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Contracts;

use App\Domain\Enterprise\Entity\Ally\Ally;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Ally\AllyAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface AllyContract
{
    public function all(string|null $search, int|null $perPage, EntityId $entityId, AllyAllRequest $request): LengthAwarePaginator;

    public function find(AllyId $AllyId): ?Ally;

    public function store(Ally $Ally): Ally;

    public function update(Ally $Ally, AllyId $AllyId): Ally;

    public function delete(AllyId $AllyId): Ally;

    public function restore(AllyId $AllyId): Ally;
}
