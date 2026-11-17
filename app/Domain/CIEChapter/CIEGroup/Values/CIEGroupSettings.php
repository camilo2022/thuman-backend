<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\Values;

final class CIEGroupSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        $this->value = $settings;
    }

    public function value(): array
    {
        return $this->value;
    }
}
