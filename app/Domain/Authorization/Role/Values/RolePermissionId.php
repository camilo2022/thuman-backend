<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Role\Values;

final class RolePermissionId
{
    private $value;

    public function __construct(int|null $permissionId = null)
    {
        $this->value = $permissionId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
