<?php

declare(strict_types=1);

namespace App\Domain\Position\Values;

use Illuminate\Support\Collection;

final class PositionRelationships
{
    private ?object $area;
    private ?object $profile_category;
    private Collection $roles;
    private Collection $permissions;

    public function __construct(
        ?object $area = null,
        ?object $profile_category = null,
        ?Collection $roles = null,
        ?Collection $permissions = null
    ) {
        $this->area = $area;
        $this->profile_category = $profile_category;
        $this->roles = $roles ?? collect();
        $this->permissions = $permissions ?? collect();
    }

    public function area(): ?object
    {
        return $this->area;
    }

    public function profileCategory(): ?object
    {
        return $this->profile_category;
    }

    public function roles(): Collection
    {
        return $this->roles;
    }

    public function permissions(): Collection
    {
        return $this->permissions;
    }

    public function toArray(): array
    {
        return [
            'area' => $this->area,
            'profile_category' => $this->profile_category,
            'roles' => $this->roles,
            'permissions' => $this->permissions
        ];
    }
}
