<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Permission\Values;

final class PermissionTitle
{
    private $value;

    public function __construct(string $title)
    {
        $this->value = $title;
    }

    public function value(): string
    {
        return $this->value;
    }
}
