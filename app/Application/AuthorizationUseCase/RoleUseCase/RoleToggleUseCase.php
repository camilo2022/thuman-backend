<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\RoleUseCase;

use App\Domain\Authorization\Role\Contracts\RoleContract;
use App\Domain\Authorization\Role\Role;
use App\Domain\Authorization\Role\Values\RoleDescription;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Domain\Authorization\Role\Values\RoleName;
use App\Domain\Authorization\Role\Values\RolePermissionId;
use App\Domain\Authorization\Role\Values\RoleTitle;
use App\Http\Requests\Authorization\Role\RoleToggleRequest;

final class RoleToggleUseCase
{
    private $roleContract;

    public function __construct(RoleContract $roleContract)
    {
        $this->roleContract = $roleContract;
    }

    public function __invoke(RoleToggleRequest $request, RoleId $roleId): Role
    {
        return $this->roleContract->toggle(
            new Role(
                new RoleId($roleId->value()),
                new RoleName($request->input('name')),
                new RoleTitle($request->input('title')),
                new RoleDescription($request->input('description')),
                new RolePermissionId($request->integer('permission_id'))
            ),
            $roleId
        );
    }
}
