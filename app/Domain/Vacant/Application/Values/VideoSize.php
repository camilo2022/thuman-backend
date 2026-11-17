<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class VideoSize
{
    private $value;

    public function __construct(string|int $size)
    {
        $this->value = $size;
    }

    public function value(): string|int
    {
        return $this->value;
    }
}
