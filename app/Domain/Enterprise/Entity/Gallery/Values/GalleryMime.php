<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Gallery\Values;

final class GalleryMime
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
