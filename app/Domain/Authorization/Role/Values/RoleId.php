<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Role\Values;

final class RoleId
{
    private $value;

    public function __construct(int|null $id)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
