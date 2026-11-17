<?php

declare(strict_types=1);

namespace App\Application\AuthUseCase;

use App\Domain\Auth\Contracts\AuthContract;
use App\Domain\Auth\Auth;
use App\Domain\Auth\Values\AuthDocumentNumber;
use App\Domain\Auth\Values\AuthEmail;
use App\Domain\Auth\Values\AuthPassword;
use App\Domain\Auth\Values\AuthPersonId;
use App\Domain\Auth\Values\AuthToken;
use App\Domain\Auth\Values\AuthUserId;
use App\Http\Requests\Auth\AuthLoginRequest;

final class AuthLoginUseCase
{
    private $authContract;

    public function __construct(AuthContract $authContract)
    {
        $this->authContract = $authContract;
    }

    public function __invoke(AuthLoginRequest $request): ?Auth
    {
        return $this->authContract->login(
            new Auth(
                new AuthPersonId(null),
                new AuthUserId(null),
                new AuthDocumentNumber($request->input('document_number')),
                new AuthEmail($request->input('email')),
                new AuthPassword($request->input('password')),
                new AuthToken(null)
            )
        );
    }
}
