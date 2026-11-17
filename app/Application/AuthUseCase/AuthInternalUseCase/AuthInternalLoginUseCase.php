<?php

declare(strict_types=1);

namespace App\Application\AuthUseCase\AuthInternalUseCase;

use App\Domain\Auth\AuthInternal\AuthInternal;
use App\Domain\Auth\AuthInternal\Contracts\AuthInternalContract;
use App\Domain\Auth\AuthInternal\Values\AuthInternalDocumentNumber;
use App\Domain\Auth\AuthInternal\Values\AuthInternalEmail;
use App\Domain\Auth\AuthInternal\Values\AuthInternalPassword;
use App\Domain\Auth\AuthInternal\Values\AuthInternalEmployeeId;
use App\Domain\Auth\AuthInternal\Values\AuthInternalPermissions;
use App\Domain\Auth\AuthInternal\Values\AuthInternalRoles;
use App\Domain\Auth\AuthInternal\Values\AuthInternalToken;
use App\Domain\Auth\AuthInternal\Values\AuthInternalUserId;
use App\Http\Requests\Auth\AuthInternal\AuthInternalLoginRequest;

final class AuthInternalLoginUseCase
{
    private $authInternalContract;

    public function __construct(AuthInternalContract $authInternalContract)
    {
        $this->authInternalContract = $authInternalContract;
    }

    public function __invoke(AuthInternalLoginRequest $request): ?AuthInternal
    {
        return $this->authInternalContract->login(
            new AuthInternal(
                new AuthInternalEmployeeId(null),
                new AuthInternalUserId(null),
                new AuthInternalDocumentNumber($request->input('document_number')),
                new AuthInternalEmail($request->input('email')),
                new AuthInternalPassword($request->input('password')),
                new AuthInternalToken(null),
                new AuthInternalRoles(collect()),
                new AuthInternalPermissions(collect())
            )
        );
    }
}
