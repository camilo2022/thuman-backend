<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule;

use App\Domain\Navigation\Module\Submodule\Values\SubmoduleCreatedAt;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleDeletedAt;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleIcon;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleModelId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleModelType;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleName;
use App\Domain\Navigation\Module\Submodule\Values\SubmodulePermissionId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRelationships;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRoleId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRoute;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleType;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleUpdatedAt;

final class Submodule
{
    private $id;
    private $modelId;
    private $modelType;
    private $roleId;
    private $permissionId;
    private $type;
    private $name;
    private $route;
    private $icon;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?SubmoduleId $id,
        SubmoduleModelId $modelId,
        SubmoduleModelType $modelType,
        SubmoduleRoleId $roleId,
        SubmodulePermissionId $permissionId,
        SubmoduleType $type,
        SubmoduleName $name,
        SubmoduleRoute $route,
        SubmoduleIcon $icon,
        ?SubmoduleCreatedAt $createdAt = null,
        ?SubmoduleUpdatedAt $updatedAt = null,
        ?SubmoduleDeletedAt $deletedAt = null,
        ?SubmoduleRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->roleId = $roleId;
        $this->permissionId = $permissionId;
        $this->type = $type;
        $this->name = $name;
        $this->route = $route;
        $this->icon = $icon;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?SubmoduleId
    {
        return $this->id;
    }

    public function modelId(): SubmoduleModelId
    {
        return $this->modelId;
    }

    public function modelType(): SubmoduleModelType
    {
        return $this->modelType;
    }

    public function roleId(): SubmoduleRoleId
    {
        return $this->roleId;
    }

    public function permissionId(): SubmodulePermissionId
    {
        return $this->permissionId;
    }

    public function type(): SubmoduleType
    {
        return $this->type;
    }

    public function name(): SubmoduleName
    {
        return $this->name;
    }

    public function route(): SubmoduleRoute
    {
        return $this->route;
    }

    public function icon(): SubmoduleIcon
    {
        return $this->icon;
    }

    public function createdAt(): ?SubmoduleCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SubmoduleUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?SubmoduleDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?SubmoduleRelationships
    {
        return $this->relationships;
    }
}
