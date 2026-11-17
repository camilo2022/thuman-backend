<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal\Values;

use Illuminate\Support\Collection;

final class AuthInternalRoles
{
    private $value;

    public function __construct(Collection $roles)
    {
        $this->value = $roles;
    }

    public function value(): Collection
    {
        return $this->value;
    }
}
