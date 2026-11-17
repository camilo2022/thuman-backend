<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Permission;

use App\Application\AuthorizationUseCase\PermissionUseCase\PermissionUpdateUseCase;
use App\Domain\Authorization\Permission\Values\PermissionId;
use App\Http\Requests\Authorization\Permission\PermissionUpdateRequest;
use App\Http\Resources\Authorization\Permission\PermissionResource;
use App\Infrastructure\Repositories\PermissionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PermissionUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(PermissionUpdateRequest $request, int $permissionId)
    {
        try {
            $permissionId = new PermissionId($permissionId ?? null);
            $permissionUpdateUseCase = new PermissionUpdateUseCase($this->permissionRepository);
            $permission = new PermissionResource($permissionUpdateUseCase->__invoke($request, $permissionId));
            return $this->successResponse(
                $permission,
                'El permiso fue actualizado exitosamente.',
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
