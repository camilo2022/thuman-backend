<?php

declare(strict_types=1);

namespace App\Domain\NotificationChannel\Values;

final class NotificationChannelName
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
