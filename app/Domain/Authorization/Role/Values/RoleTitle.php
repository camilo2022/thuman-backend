<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Role\Values;

final class RoleTitle
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
