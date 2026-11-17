<?php

declare(strict_types=1);

namespace App\Domain\Profile\Values;

final class ProfileTitle
{
    private $value;

    public function __construct(string $title)
    {
        $this->value = $title;
    }

    public function value(): string
    {
        return $this->value;
    }
}
