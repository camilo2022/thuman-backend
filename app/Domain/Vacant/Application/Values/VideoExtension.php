<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class VideoExtension
{
    private $value;

    public function __construct(string $extension)
    {
        $this->value = $extension;
    }

    public function value(): string
    {
        return $this->value;
    }
}
