<?php

declare(strict_types=1);

namespace App\Domain\NotificationType\Values;

final class NotificationTypeDescription
{
    private $value;

    public function __construct(string $description)
    {
        $this->value = $description;
    }

    public function value(): string
    {
        return $this->value;
    }
}
