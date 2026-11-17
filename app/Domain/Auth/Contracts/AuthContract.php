<?php

declare(strict_types=1);

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\Auth;
use App\Domain\Auth\Values\AuthUserId;

interface AuthContract
{
    public function login(Auth $auth): ?Auth;

    public function logout(AuthUserId $userId): ?Auth;
}
