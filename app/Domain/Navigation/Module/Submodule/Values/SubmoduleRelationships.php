<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmoduleRelationships
{
    private ?object $model;
    private ?object $role;
    private ?object $permission;

    public function __construct(
        ?object $model = null,
        ?object $role = null,
        ?object $permission = null
    ) {
        $this->model = $model;
        $this->role = $role;
        $this->permission = $permission;
    }

    public function model(): ?object
    {
        return $this->model;
    }

    public function role(): ?object
    {
        return $this->role;
    }

    public function permission(): ?object
    {
        return $this->permission;
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'role' => $this->role,
            'permission' => $this->permission
        ];
    }
}
