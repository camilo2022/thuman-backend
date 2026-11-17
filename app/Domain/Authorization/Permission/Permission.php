<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Permission;

use App\Domain\Authorization\Permission\Values\PermissionCreatedAt;
use App\Domain\Authorization\Permission\Values\PermissionDescription;
use App\Domain\Authorization\Permission\Values\PermissionId;
use App\Domain\Authorization\Permission\Values\PermissionName;
use App\Domain\Authorization\Permission\Values\PermissionRelationships;
use App\Domain\Authorization\Permission\Values\PermissionTitle;
use App\Domain\Authorization\Permission\Values\PermissionUpdatedAt;

final class Permission
{
    private $id;
    private $name;
    private $title;
    private $description;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?PermissionId $id,
        PermissionName $name,
        PermissionTitle $title,
        PermissionDescription $description,
        ?PermissionCreatedAt $createdAt = null,
        ?PermissionUpdatedAt $updatedAt = null,
        ?PermissionRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?PermissionId
    {
        return $this->id;
    }

    public function name(): PermissionName
    {
        return $this->name;
    }

    public function title(): PermissionTitle
    {
        return $this->title;
    }

    public function description(): PermissionDescription
    {
        return $this->description;
    }

    public function createdAt(): ?PermissionCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PermissionUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?PermissionRelationships
    {
        return $this->relationships;
    }
}
