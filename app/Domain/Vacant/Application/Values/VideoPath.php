<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class VideoPath
{
    private $value;

    public function __construct(string $path)
    {
        $this->value = $path;
    }

    public function value(): string
    {
        return $this->value;
    }
}
