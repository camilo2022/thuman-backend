<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Values\UserCreatedAt;
use App\Domain\User\Values\UserDeletedAt;
use App\Domain\User\Values\UserId;
use App\Domain\User\Values\UserPassword;
use App\Domain\User\Values\UserPersonId;
use App\Domain\User\Values\UserPermissionId;
use App\Domain\User\Values\UserRelationships;
use App\Domain\User\Values\UserUpdatedAt;

final class User
{
    private $id;
    private $personId;
    private $password;
    private $permissionId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?UserId $id,
        UserPersonId $personId,
        UserPassword $password,
        ?UserPermissionId $permissionId = null,
        ?UserCreatedAt $createdAt = null,
        ?UserUpdatedAt $updatedAt = null,
        ?UserDeletedAt $deletedAt = null,
        ?UserRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->password = $password;
        $this->permissionId = $permissionId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?UserId
    {
        return $this->id;
    }

    public function personId(): UserPersonId
    {
        return $this->personId;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function permissionId(): ?UserPermissionId
    {
        return $this->permissionId;
    }

    public function createdAt(): ?UserCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?UserUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?UserDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?UserRelationships
    {
        return $this->relationships;
    }
}
