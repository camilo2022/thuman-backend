<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Role;

use App\Application\AuthorizationUseCase\RoleUseCase\RoleStoreUseCase;
use App\Http\Requests\Authorization\Role\RoleStoreRequest;
use App\Http\Resources\Authorization\Role\RoleResource;
use App\Infrastructure\Repositories\RoleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RoleStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RoleStoreRequest $request)
    {
        try {
            $roleStoreUseCase = new RoleStoreUseCase($this->roleRepository);
            $role = new RoleResource($roleStoreUseCase->__invoke($request));
            return $this->successResponse(
                $role,
                'El rol fue registrado exitosamente.',
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
