<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Values;

final class SubmoduleRoleId
{
    private $value;

    public function __construct(int|null $roleId)
    {
        $this->value = $roleId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
