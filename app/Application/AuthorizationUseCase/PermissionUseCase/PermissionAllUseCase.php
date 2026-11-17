<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\PermissionUseCase;

use App\Domain\Authorization\Permission\Contracts\PermissionContract;
use App\Http\Requests\Authorization\Permission\PermissionAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class PermissionAllUseCase
{
    private $permissionContract;

    public function __construct(PermissionContract $permissionContract)
    {
        $this->permissionContract = $permissionContract;
    }

    public function __invoke(PermissionAllRequest $request): LengthAwarePaginator
    {
        return $this->permissionContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
