<?php

declare(strict_types=1);

namespace App\Application\AuthUseCase\AuthInternalUseCase;

use App\Domain\Auth\AuthInternal\Contracts\AuthInternalContract;
use App\Domain\Auth\AuthInternal\AuthInternal;
use App\Domain\Auth\AuthInternal\Values\AuthInternalUserId;
use App\Http\Requests\Auth\AuthInternal\AuthInternalLogoutRequest;

final class AuthInternalLogoutUseCase
{
    private $authInternalContract;

    public function __construct(AuthInternalContract $authInternalContract)
    {
        $this->authInternalContract = $authInternalContract;
    }

    public function __invoke(AuthInternalLogoutRequest $request): ?AuthInternal
    {
        return $this->authInternalContract->logout(new AuthInternalUserId($request->input('user_id')));
    }
}
