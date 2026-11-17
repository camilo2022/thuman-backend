<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Permission\Values;

final class PermissionUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
