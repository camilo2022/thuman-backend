<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Authorization\Role;

use App\Application\AuthorizationUseCase\RoleUseCase\RoleAllUseCase;
use App\Http\Requests\Authorization\Role\RoleAllRequest;
use App\Http\Resources\Authorization\Role\RoleCollection;
use App\Infrastructure\Repositories\RoleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RoleAllController
{
    use ApiResponser;
    use ApiMessage;
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RoleAllRequest $request)
    {
        try {
            $roleAllUseCase = new RoleAllUseCase($this->roleRepository);
            $roles = new RoleCollection($roleAllUseCase->__invoke($request));
            return $this->successResponse(
                $roles,
                'Los roles consultados exitosamente.',
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
