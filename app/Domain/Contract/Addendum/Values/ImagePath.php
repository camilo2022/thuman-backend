<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class ImagePath
{
    private $value;

    public function __construct(string $path)
    {
        $this->value = $path;
    }

    public function value(): string
    {
        return $this->value;
    }
}
