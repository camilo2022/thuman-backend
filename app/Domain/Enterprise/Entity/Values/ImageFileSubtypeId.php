<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

final class ImageFileSubtypeId
{
    private $value;

    public function __construct(int $fileSubtypeId)
    {
        $this->value = $fileSubtypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
