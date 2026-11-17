<?php

declare(strict_types=1);

namespace App\Application\UserUseCase;

use App\Domain\User\Contracts\UserContract;
use App\Domain\User\User;
use App\Domain\User\Values\UserId;
use App\Domain\User\Values\UserPassword;
use App\Domain\User\Values\UserPersonId;
use App\Http\Requests\User\UserStoreRequest;

final class UserStoreUseCase
{
    private $userContract;

    public function __construct(UserContract $userContract)
    {
        $this->userContract = $userContract;
    }

    public function __invoke(UserStoreRequest $request): User
    {
        return $this->userContract->store(
            new User(
                new UserId($request->input('id', null)),
                new UserPersonId($request->integer('person_id')),
                new UserPassword($request->input('password')),
            )
        );
    }
}
