<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Gallery\Values;

final class GalleryName
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
