<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class ImageExtension
{
    private $value;

    public function __construct(string $extension)
    {
        $this->value = $extension;
    }

    public function value(): string
    {
        return $this->value;
    }
}
