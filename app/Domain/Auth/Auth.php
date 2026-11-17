<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Auth\Values\AuthDocumentNumber;
use App\Domain\Auth\Values\AuthEmail;
use App\Domain\Auth\Values\AuthUserId;
use App\Domain\Auth\Values\AuthPassword;
use App\Domain\Auth\Values\AuthPersonId;
use App\Domain\Auth\Values\AuthToken;

final class Auth
{
    private $personId;
    private $userId;
    private $documentNumber;
    private $email;
    private $password;
    private $token;

    public function __construct(
        ?AuthPersonId $personId,
        ?AuthUserId $userId,
        ?AuthDocumentNumber $documentNumber = null,
        ?AuthEmail $email = null,
        AuthPassword $password,
        ?AuthToken $token = null
    )
    {
        $this->personId = $personId;
        $this->userId = $userId;
        $this->documentNumber = $documentNumber;
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
    }

    public function personId(): ?AuthPersonId
    {
        return $this->personId;
    }

    public function userId(): ?AuthUserId
    {
        return $this->userId;
    }

    public function documentNumber(): ?AuthDocumentNumber
    {
        return $this->documentNumber;
    }

    public function email(): ?AuthEmail
    {
        return $this->email;
    }

    public function password(): AuthPassword
    {
        return $this->password;
    }

    public function token(): ?AuthToken
    {
        return $this->token;
    }
}
