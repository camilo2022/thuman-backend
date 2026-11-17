<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Role;

use App\Application\AuthorizationUseCase\RoleUseCase\RoleToggleUseCase;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Http\Requests\Authorization\Role\RoleToggleRequest;
use App\Http\Resources\Authorization\Role\RoleResource;
use App\Infrastructure\Repositories\RoleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RoleToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RoleToggleRequest $request, int $roleId)
    {
        try {
            $roleId = new RoleId($roleId ?? null);
            $roleToggleUseCase = new RoleToggleUseCase($this->roleRepository);
            $role = new RoleResource($roleToggleUseCase->__invoke($request, $roleId));
            return $this->successResponse(
                $role,
                'El permiso fue asignado/removido al rol exitosamente.',
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
