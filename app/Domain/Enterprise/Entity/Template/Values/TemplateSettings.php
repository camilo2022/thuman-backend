<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Template\Values;

final class TemplateSettings
{
    private $value;

    public function __construct(object|array|null $settings)
    {
        if (is_null($settings)) {
            $settings = [];
        }

        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function margins(): array
    {
        return $this->value['margins']
            ?? ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20];
    }

    public function orientation(): string
    {
        return $this->value['orientation']
            ?? 'portrait';
    }

    public function size(): string
    {
        return $this->value['size']
            ?? 'letter';
    }

    public function sections(): array
    {
        return $this->value['sections']
            ?? [];
    }
}
