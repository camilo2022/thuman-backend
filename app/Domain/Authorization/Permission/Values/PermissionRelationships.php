<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Permission\Values;

use Illuminate\Support\Collection;

final class PermissionRelationships
{
    private Collection $roles;

    public function __construct(
        ?Collection $roles = null
    ) {
        $this->roles = $roles ?? collect();
    }

    public function roles(): Collection
    {
        return $this->roles;
    }

    public function toArray(): array
    {
        return [
            'roles' => $this->roles
        ];
    }
}
