<?php

declare(strict_types=1);

namespace App\Domain\User\Values;

use Illuminate\Support\Collection;

final class UserRelationships
{
    private ?object $person;
    private Collection $roles;
    private Collection $permissions;

    public function __construct(
        ?object $person = null,
        ?Collection $roles = null,
        ?Collection $permissions = null
    ) {
        $this->person = $person;
        $this->roles = $roles ?? collect();
        $this->permissions = $permissions ?? collect();
    }

    public function person(): ?object
    {
        return $this->person;
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
            'person' => $this->person,
            'roles' => $this->roles,
            'permissions' => $this->permissions
        ];
    }
}
