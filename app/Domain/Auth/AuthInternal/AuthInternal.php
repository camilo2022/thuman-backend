<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal;

use App\Domain\Auth\AuthInternal\Values\AuthInternalDocumentNumber;
use App\Domain\Auth\AuthInternal\Values\AuthInternalEmail;
use App\Domain\Auth\AuthInternal\Values\AuthInternalUserId;
use App\Domain\Auth\AuthInternal\Values\AuthInternalPassword;
use App\Domain\Auth\AuthInternal\Values\AuthInternalPermissions;
use App\Domain\Auth\AuthInternal\Values\AuthInternalEmployeeId;
use App\Domain\Auth\AuthInternal\Values\AuthInternalRoles;
use App\Domain\Auth\AuthInternal\Values\AuthInternalToken;

final class AuthInternal
{
    private $employeeId;
    private $userId;
    private $documentNumber;
    private $email;
    private $password;
    private $token;
    private $roles;
    private $permissions;

    public function __construct(
        ?AuthInternalEmployeeId $employeeId,
        ?AuthInternalUserId $userId,
        ?AuthInternalDocumentNumber $documentNumber = null,
        ?AuthInternalEmail $email = null,
        AuthInternalPassword $password,
        ?AuthInternalToken $token = null,
        AuthInternalRoles $roles,
        AuthInternalPermissions $permissions,
    )
    {
        $this->employeeId = $employeeId;
        $this->userId = $userId;
        $this->documentNumber = $documentNumber;
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
        $this->roles = $roles;
        $this->permissions = $permissions;
    }

    public function employeeId(): ?AuthInternalEmployeeId
    {
        return $this->employeeId;
    }

    public function userId(): ?AuthInternalUserId
    {
        return $this->userId;
    }

    public function documentNumber(): ?AuthInternalDocumentNumber
    {
        return $this->documentNumber;
    }

    public function email(): ?AuthInternalEmail
    {
        return $this->email;
    }

    public function password(): AuthInternalPassword
    {
        return $this->password;
    }

    public function token(): ?AuthInternalToken
    {
        return $this->token;
    }

    public function roles(): ?AuthInternalRoles
    {
        return $this->roles;
    }

    public function permissions(): ?AuthInternalPermissions
    {
        return $this->permissions;
    }
}
