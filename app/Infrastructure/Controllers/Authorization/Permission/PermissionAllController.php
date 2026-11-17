<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Permission;

use App\Application\AuthorizationUseCase\PermissionUseCase\PermissionAllUseCase;
use App\Http\Requests\Authorization\Permission\PermissionAllRequest;
use App\Http\Resources\Authorization\Permission\PermissionCollection;
use App\Infrastructure\Repositories\PermissionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PermissionAllController
{
    use ApiResponser;
    use ApiMessage;
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(PermissionAllRequest $request)
    {
        try {
            $permissionAllUseCase = new PermissionAllUseCase($this->permissionRepository);
            $permissions = new PermissionCollection($permissionAllUseCase->__invoke($request));
            return $this->successResponse(
                $permissions,
                'Los permisos consultados exitosamente.',
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
