<?php

declare(strict_types=1);

namespace App\Application\AuthUseCase;

use App\Domain\Auth\Contracts\AuthContract;
use App\Domain\Auth\Auth;
use App\Domain\Auth\Values\AuthUserId;
use App\Http\Requests\Auth\AuthLogoutRequest;

final class AuthLogoutUseCase
{
    private $authContract;

    public function __construct(AuthContract $authContract)
    {
        $this->authContract = $authContract;
    }

    public function __invoke(AuthLogoutRequest $request): ?Auth
    {
        return $this->authContract->logout(new AuthUserId($request->input('user_id')));
    }
}
