<?php

declare(strict_types=1);

namespace App\Domain\NotificationChannel\Values;

final class NotificationChannelItemId
{
    private $value;

    public function __construct(int $itemId)
    {
        $this->value = $itemId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
