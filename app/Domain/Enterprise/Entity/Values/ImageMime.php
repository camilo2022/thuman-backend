<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

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
