<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Permission;

use App\Application\AuthorizationUseCase\PermissionUseCase\PermissionFindUseCase;
use App\Domain\Authorization\Permission\Values\PermissionId;
use App\Http\Requests\Authorization\Permission\PermissionFindRequest;
use App\Http\Resources\Authorization\Permission\PermissionResource;
use App\Infrastructure\Repositories\PermissionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PermissionFindController
{
    use ApiResponser;
    use ApiMessage;
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(PermissionFindRequest $request, int|null $permissionId = null)
    {
        try {
            $permissionId = new PermissionId($permissionId);
            $permissionFindUseCase = new PermissionFindUseCase($this->permissionRepository);
            $permission = new PermissionResource($permissionFindUseCase->__invoke($request, $permissionId));
            return $this->successResponse(
                $permission,
                'El permiso fue encontrada exitosamente.',
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
