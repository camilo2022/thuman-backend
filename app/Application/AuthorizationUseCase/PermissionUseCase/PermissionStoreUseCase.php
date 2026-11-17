<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\PermissionUseCase;

use App\Domain\Authorization\Permission\Contracts\PermissionContract;
use App\Domain\Authorization\Permission\Permission;
use App\Domain\Authorization\Permission\Values\PermissionDescription;
use App\Domain\Authorization\Permission\Values\PermissionId;
use App\Domain\Authorization\Permission\Values\PermissionName;
use App\Domain\Authorization\Permission\Values\PermissionTitle;
use App\Http\Requests\Authorization\Permission\PermissionStoreRequest;

final class PermissionStoreUseCase
{
    private $permissionContract;

    public function __construct(PermissionContract $permissionContract)
    {
        $this->permissionContract = $permissionContract;
    }

    public function __invoke(PermissionStoreRequest $request): Permission
    {
        return $this->permissionContract->store(
            new Permission(
                new PermissionId(null),
                new PermissionName($request->input('name')),
                new PermissionTitle($request->input('title')),
                new PermissionDescription($request->input('description'))
            )
        );
    }
}
