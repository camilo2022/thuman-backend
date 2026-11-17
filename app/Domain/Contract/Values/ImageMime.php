<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ImageMime
{
    private $value;

    public function __construct(string $mime)
    {
        $this->value = $mime;
    }

    public function value(): string
    {
        return $this->value;
    }
}
