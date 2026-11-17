<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\RoleUseCase;

use App\Domain\Authorization\Role\Role;
use App\Domain\Authorization\Role\Contracts\RoleContract;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Http\Requests\Authorization\Role\RoleFindRequest;

final class RoleFindUseCase
{
    private $roleContract;

    public function __construct(RoleContract $roleContract)
    {
        $this->roleContract = $roleContract;
    }

    public function __invoke(RoleFindRequest $request, RoleId $roleId): ?Role
    {
        return $this->roleContract->find($request, $roleId);
    }
}
