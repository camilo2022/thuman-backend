<?php

declare(strict_types=1);

namespace App\Domain\Auth\AuthInternal\Values;

use Illuminate\Support\Collection;

final class AuthInternalPermissions
{
    private $value;

    public function __construct(Collection $permissions)
    {
        $this->value = $permissions;
    }

    public function value(): Collection
    {
        return $this->value;
    }
}
