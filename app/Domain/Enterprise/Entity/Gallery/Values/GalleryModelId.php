<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Gallery\Values;

final class GalleryModelId
{
    private $value;

    public function __construct(int $modelId)
    {
        $this->value = $modelId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
