<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Permission;

use App\Application\AuthorizationUseCase\PermissionUseCase\PermissionStoreUseCase;
use App\Http\Requests\Authorization\Permission\PermissionStoreRequest;
use App\Http\Resources\Authorization\Permission\PermissionResource;
use App\Infrastructure\Repositories\PermissionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PermissionStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(PermissionStoreRequest $request)
    {
        try {
            $permissionStoreUseCase = new PermissionStoreUseCase($this->permissionRepository);
            $permission = new PermissionResource($permissionStoreUseCase->__invoke($request));
            return $this->successResponse(
                $permission,
                'El permiso fue registrado exitosamente.',
                201
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
