<?php

declare(strict_types=1);

namespace App\Application\NotificationChannelUseCase;

use App\Domain\NotificationChannel\NotificationChannel;
use App\Domain\NotificationChannel\Contracts\NotificationChannelContract;
use App\Domain\NotificationChannel\Values\NotificationChannelId;

final class NotificationChannelFindUseCase
{
    private $notificationChannelContract;

    public function __construct(NotificationChannelContract $notificationChannelContract)
    {
        $this->notificationChannelContract = $notificationChannelContract;
    }

    public function __invoke(NotificationChannelId $notificationChannelId): ?NotificationChannel
    {
        return $this->notificationChannelContract->find($notificationChannelId);
    }
}
