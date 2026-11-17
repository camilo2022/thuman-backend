<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Permission\Contracts;

use App\Domain\Authorization\Permission\Permission;
use App\Domain\Authorization\Permission\Values\PermissionId;
use App\Http\Requests\Authorization\Permission\PermissionAllRequest;
use App\Http\Requests\Authorization\Permission\PermissionFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionContract
{
    public function all(string|null $search, int|null $perPage, PermissionAllRequest $request): LengthAwarePaginator;

    public function find(PermissionFindRequest $request, PermissionId $permissionId): ?Permission;

    public function store(Permission $permission): Permission;

    public function update(Permission $permission, PermissionId $permissionId): Permission;
}
