<?php

declare(strict_types=1);

namespace App\Application\UserUseCase;

use App\Domain\User\Contracts\UserContract;
use App\Domain\User\User;
use App\Domain\User\Values\UserId;
use App\Http\Requests\User\UserFindRequest;

final class UserFindUseCase
{
    private $personContract;

    public function __construct(UserContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(UserFindRequest $request, UserId $personId): ?User
    {
        return $this->personContract->find($request, $personId);
    }
}
