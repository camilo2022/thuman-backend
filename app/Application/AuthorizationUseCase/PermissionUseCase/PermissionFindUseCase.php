<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\PermissionUseCase;

use App\Domain\Authorization\Permission\Permission;
use App\Domain\Authorization\Permission\Contracts\PermissionContract;
use App\Domain\Authorization\Permission\Values\PermissionId;
use App\Http\Requests\Authorization\Permission\PermissionFindRequest;

final class PermissionFindUseCase
{
    private $permissionContract;

    public function __construct(PermissionContract $permissionContract)
    {
        $this->permissionContract = $permissionContract;
    }

    public function __invoke(PermissionFindRequest $request, PermissionId $permissionId): ?Permission
    {
        return $this->permissionContract->find($request, $permissionId);
    }
}
