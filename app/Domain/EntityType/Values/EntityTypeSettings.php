<?php

declare(strict_types=1);

namespace App\Domain\EntityType\Values;

final class EntityTypeSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function abreviature(): string
    {
        return $this->value['abreviature'];
    }
}
