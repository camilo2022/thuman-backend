<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal\Contracts;

use App\Domain\Auth\AuthInternal\AuthInternal;
use App\Domain\Auth\AuthInternal\Values\AuthInternalUserId;

interface AuthInternalContract
{
    public function login(AuthInternal $auth): ?AuthInternal;

    public function logout(AuthInternalUserId $userId): ?AuthInternal;
}
