<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\RoleUseCase;

use App\Domain\Authorization\Role\Contracts\RoleContract;
use App\Domain\Authorization\Role\Role;
use App\Domain\Authorization\Role\Values\RoleDescription;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Domain\Authorization\Role\Values\RoleName;
use App\Domain\Authorization\Role\Values\RoleTitle;
use App\Http\Requests\Authorization\Role\RoleStoreRequest;

final class RoleStoreUseCase
{
    private $roleContract;

    public function __construct(RoleContract $roleContract)
    {
        $this->roleContract = $roleContract;
    }

    public function __invoke(RoleStoreRequest $request): Role
    {
        return $this->roleContract->store(
            new Role(
                new RoleId(null),
                new RoleName($request->input('name')),
                new RoleTitle($request->input('title')),
                new RoleDescription($request->input('description'))
            )
        );
    }
}
