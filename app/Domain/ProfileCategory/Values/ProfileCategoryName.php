<?php

declare(strict_types=1);

namespace App\Domain\ProfileCategory\Values;

final class ProfileCategoryName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
