<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module;

use App\Domain\Navigation\Module\Values\ModuleCreatedAt;
use App\Domain\Navigation\Module\Values\ModuleDeletedAt;
use App\Domain\Navigation\Module\Values\ModuleRoute;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Domain\Navigation\Module\Values\ModuleName;
use App\Domain\Navigation\Module\Values\ModuleType;
use App\Domain\Navigation\Module\Values\ModuleUpdatedAt;

final class Module
{
    private $id;
    private $name;
    private $type;
    private $route;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?ModuleId $id,
        ModuleName $name,
        ModuleType $type,
        ModuleRoute $route,
        ?ModuleCreatedAt $createdAt = null,
        ?ModuleUpdatedAt $updatedAt = null,
        ?ModuleDeletedAt $deletedAt = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->route = $route;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function id(): ?ModuleId
    {
        return $this->id;
    }

    public function name(): ModuleName
    {
        return $this->name;
    }

    public function type(): ModuleType
    {
        return $this->type;
    }

    public function route(): ModuleRoute
    {
        return $this->route;
    }

    public function createdAt(): ?ModuleCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ModuleUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ModuleDeletedAt
    {
        return $this->deletedAt;
    }
}
