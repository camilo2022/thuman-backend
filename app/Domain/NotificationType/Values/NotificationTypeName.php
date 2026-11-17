<?php

declare(strict_types=1);

namespace App\Domain\NotificationType\Values;

final class NotificationTypeName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
