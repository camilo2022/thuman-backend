<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Role\Values;

use Illuminate\Support\Collection;

final class RoleRelationships
{
    private Collection $permissions;

    public function __construct(
        ?Collection $permissions = null
    ) {
        $this->permissions = $permissions ?? collect();
    }

    public function permissions(): Collection
    {
        return $this->permissions;
    }

    public function toArray(): array
    {
        return [
            'permissions' => $this->permissions
        ];
    }
}
