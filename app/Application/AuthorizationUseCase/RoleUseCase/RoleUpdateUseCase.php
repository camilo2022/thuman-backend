<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\RoleUseCase;

use App\Domain\Authorization\Role\Contracts\RoleContract;
use App\Domain\Authorization\Role\Role;
use App\Domain\Authorization\Role\Values\RoleDescription;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Domain\Authorization\Role\Values\RoleName;
use App\Domain\Authorization\Role\Values\RoleTitle;
use App\Http\Requests\Authorization\Role\RoleUpdateRequest;

final class RoleUpdateUseCase
{
    private $roleContract;

    public function __construct(RoleContract $roleContract)
    {
        $this->roleContract = $roleContract;
    }

    public function __invoke(RoleUpdateRequest $request, RoleId $roleId): Role
    {
        return $this->roleContract->update(
            new Role(
                new RoleId($roleId->value()),
                new RoleName($request->input('name')),
                new RoleTitle($request->input('title')),
                new RoleDescription($request->input('description'))
            ),
            $roleId
        );
    }
}
