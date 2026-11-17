<?php

declare(strict_types=1);

namespace App\Domain\NotificationType\Values;

final class NotificationTypeSettings
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

    public function variables(): array
    {
        return $this->value['variables'] ?? [];
    }

    public function channels(): array
    {
        return $this->value['channels'] ?? [];
    }
}
