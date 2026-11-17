<?php

declare(strict_types=1);

namespace App\Application\UserUseCase;

use App\Domain\User\Contracts\UserContract;
use App\Domain\User\User;
use App\Http\Requests\User\UserRecoveryPhoneNumberRequest;

final class UserRecoveryPhoneNumberUseCase
{
    private $personContract;

    public function __construct(UserContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(UserRecoveryPhoneNumberRequest $request): ?User
    {
        return $this->personContract->recoveryPhoneNumber($request);
    }
}
