<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Role;

use App\Application\AuthorizationUseCase\RoleUseCase\RoleFindUseCase;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Http\Requests\Authorization\Role\RoleFindRequest;
use App\Http\Resources\Authorization\Role\RoleResource;
use App\Infrastructure\Repositories\RoleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RoleFindController
{
    use ApiResponser;
    use ApiMessage;
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RoleFindRequest $request, int|null $roleId = null)
    {
        try {
            $roleId = new RoleId($roleId);
            $roleFindUseCase = new RoleFindUseCase($this->roleRepository);
            $role = new RoleResource($roleFindUseCase->__invoke($request, $roleId));
            return $this->successResponse(
                $role,
                'El rol fue encontrada exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
