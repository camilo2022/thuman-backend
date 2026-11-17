<?php

declare(strict_types=1);

namespace App\Application\UserUseCase;

use App\Domain\User\Contracts\UserContract;
use App\Domain\User\User;
use App\Domain\User\Values\UserId;
use App\Domain\User\Values\UserPassword;
use App\Domain\User\Values\UserPersonId;
use App\Http\Requests\User\UserUpdateRequest;

final class UserUpdateUseCase
{
    private $personContract;

    public function __construct(UserContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(UserUpdateRequest $request, UserId $personId): User
    {
        return $this->personContract->update(
            new User(
                new UserId($personId->value()),
                new UserPersonId($request->integer('person_id')),
                new UserPassword($request->input('password')),
            ),
            $personId
        );
    }
}
