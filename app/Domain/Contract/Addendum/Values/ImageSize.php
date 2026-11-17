<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class ImageSize
{
    private $value;

    public function __construct(string|int $size)
    {
        $this->value = $size;
    }

    public function value(): string|int
    {
        return $this->value;
    }
}
