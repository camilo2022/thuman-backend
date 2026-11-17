<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Role;

use App\Domain\Authorization\Role\Values\RolePermissionId;
use App\Domain\Authorization\Role\Values\RoleCreatedAt;
use App\Domain\Authorization\Role\Values\RoleDescription;
use App\Domain\Authorization\Role\Values\RoleId;
use App\Domain\Authorization\Role\Values\RoleName;
use App\Domain\Authorization\Role\Values\RoleRelationships;
use App\Domain\Authorization\Role\Values\RoleTitle;
use App\Domain\Authorization\Role\Values\RoleUpdatedAt;

final class Role
{
    private $id;
    private $name;
    private $title;
    private $description;
    private $permissionId;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?RoleId $id,
        RoleName $name,
        RoleTitle $title,
        RoleDescription $description,
        ?RolePermissionId $permissionId = null,
        ?RoleCreatedAt $createdAt = null,
        ?RoleUpdatedAt $updatedAt = null,
        ?RoleRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->permissionId = $permissionId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?RoleId
    {
        return $this->id;
    }

    public function name(): RoleName
    {
        return $this->name;
    }

    public function title(): RoleTitle
    {
        return $this->title;
    }

    public function description(): RoleDescription
    {
        return $this->description;
    }

    public function permissionId(): ?RolePermissionId
    {
        return $this->permissionId;
    }

    public function createdAt(): ?RoleCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?RoleUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?RoleRelationships
    {
        return $this->relationships;
    }
}
