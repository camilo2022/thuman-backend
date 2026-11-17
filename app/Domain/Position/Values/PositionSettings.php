<?php

declare(strict_types=1);

namespace App\Domain\Position\Values;

final class PositionSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        $this->value = $settings;
    }

    public function value(): object|array
    {
        return $this->value;
    }

    public function polygraph(): bool
    {
        return $this->value['polygraph'];
    }
}
