<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Role\Contracts;

use App\Domain\Authorization\Role\Role;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Http\Requests\Authorization\Role\RoleFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(RoleFindRequest $request, RoleId $roleId): ?Role;

    public function store(Role $role): Role;

    public function update(Role $role, RoleId $roleId): Role;

    public function toggle(Role $role, RoleId $roleId): Role;
}
