<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmodulePermissionId
{
    private $value;

    public function __construct(int|null $permissionId)
    {
        $this->value = $permissionId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
