<?php

declare(strict_types=1);

namespace App\Domain\OrganizationalStructure\Contracts;

use App\Domain\OrganizationalStructure\OrganizationalStructure;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureStatus;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrganizationalStructureContract
{
    public function all(string|null $search, int|null $perPage, OrganizationalStructureAllRequest $request): LengthAwarePaginator;

    public function find(OrganizationalStructureId $absenceTypeId): ?OrganizationalStructure;

    public function store(OrganizationalStructure $absenceType): OrganizationalStructure;

    public function update(OrganizationalStructure $absenceType, OrganizationalStructureId $absenceTypeId): OrganizationalStructure;

    public function delete(OrganizationalStructureId $absenceTypeId): OrganizationalStructure;

    public function restore(OrganizationalStructureId $absenceTypeId): OrganizationalStructure;

    public function updateStatus(OrganizationalStructureId $organizationalStructureId, OrganizationalStructureStatus $status): OrganizationalStructure;

    public function existsPath(int $entityId, int $fromPositionId, int $toPositionId, ?int $exceptId = null): bool;
}
