<?php

declare(strict_types=1);

namespace App\Application\AuthorizationUseCase\RoleUseCase;

use App\Domain\Authorization\Role\Contracts\RoleContract;
use App\Http\Requests\Authorization\Role\RoleAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class RoleAllUseCase
{
    private $roleContract;

    public function __construct(RoleContract $roleContract)
    {
        $this->roleContract = $roleContract;
    }

    public function __invoke(RoleAllRequest $request): LengthAwarePaginator
    {
        return $this->roleContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
