<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Headquarter\Contracts;

use App\Domain\Enterprise\Entity\Headquarter\Headquarter;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterId;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Headquarter\HeadquarterAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface HeadquarterContract
{
    public function all(string|null $search, int|null $perPage, EntityId $entityId, HeadquarterAllRequest $request): LengthAwarePaginator;

    public function find(HeadquarterId $headquarterId): ?Headquarter;

    public function store(Headquarter $headquarter): Headquarter;

    public function update(Headquarter $headquarter, HeadquarterId $headquarterId): Headquarter;

    public function delete(HeadquarterId $headquarterId): Headquarter;

    public function restore(HeadquarterId $headquarterId): Headquarter;
}
