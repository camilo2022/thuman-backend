<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class ImageName
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
