<?php

declare(strict_types=1);

namespace App\Application\UserUseCase;

use App\Domain\User\Contracts\UserContract;
use App\Domain\User\User;
use App\Domain\User\Values\UserId;
use App\Domain\User\Values\UserPassword;
use App\Domain\User\Values\UserPermissionId;
use App\Domain\User\Values\UserPersonId;
use App\Http\Requests\User\UserToggleRequest;

final class UserToggleUseCase
{
    private $userContract;

    public function __construct(UserContract $userContract)
    {
        $this->userContract = $userContract;
    }

    public function __invoke(UserToggleRequest $request, UserId $userId): User
    {
        return $this->userContract->toggle(
            new User(
                new UserId($userId->value()),
                new UserPersonId($request->integer('person_id')),
                new UserPassword($request->input('password')),
                new UserPermissionId($request->integer('permission_id'))
            ),
            $userId
        );
    }
}
