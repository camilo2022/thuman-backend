<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Gallery\Values;

final class GalleryMetadata
{
    private $value;

    public function __construct(object|array $metadata)
    {
        $this->value = $metadata;
    }

    public function value(): object|array
    {
        return $this->value;
    }
}
