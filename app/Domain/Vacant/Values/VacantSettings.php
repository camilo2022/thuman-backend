<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantSettings
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

    public function polygraph(): bool
    {
        return $this->value['polygraph'] ?? false;
    }

    public function videoInterview(): string
    {
        return $this->value['video_interview'] ?? true;
    }
}
