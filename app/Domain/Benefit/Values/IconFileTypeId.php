<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Values;

final class IconFileTypeId
{
    private $value;

    public function __construct(int $fileTypeId)
    {
        $this->value = $fileTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
