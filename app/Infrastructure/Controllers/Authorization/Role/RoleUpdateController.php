<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Role;

use App\Application\AuthorizationUseCase\RoleUseCase\RoleUpdateUseCase;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Http\Requests\Authorization\Role\RoleUpdateRequest;
use App\Http\Resources\Authorization\Role\RoleResource;
use App\Infrastructure\Repositories\RoleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RoleUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RoleUpdateRequest $request, int $roleId)
    {
        try {
            $roleId = new RoleId($roleId ?? null);
            $roleUpdateUseCase = new RoleUpdateUseCase($this->roleRepository);
            $role = new RoleResource($roleUpdateUseCase->__invoke($request, $roleId));
            return $this->successResponse(
                $role,
                'El rol fue actualizado exitosamente.',
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
