<?php

declare(strict_types=1);

namespace App\Application\UserUseCase;

use App\Domain\User\Contracts\UserContract;
use App\Http\Requests\User\UserAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserAllUseCase
{
    private $userContract;

    public function __construct(UserContract $userContract)
    {
        $this->userContract = $userContract;
    }

    public function __invoke(UserAllRequest $request): LengthAwarePaginator
    {
        return $this->userContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
