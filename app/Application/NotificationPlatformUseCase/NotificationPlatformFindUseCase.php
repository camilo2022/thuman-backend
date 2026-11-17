<?php

declare(strict_types=1);

namespace App\Application\NotificationPlatformUseCase;

use App\Domain\NotificationPlatform\NotificationPlatform;
use App\Domain\NotificationPlatform\Contracts\NotificationPlatformContract;
use App\Domain\NotificationPlatform\Values\NotificationPlatformId;

final class NotificationPlatformFindUseCase
{
    private $notificationPlatformContract;

    public function __construct(NotificationPlatformContract $notificationPlatformContract)
    {
        $this->notificationPlatformContract = $notificationPlatformContract;
    }

    public function __invoke(NotificationPlatformId $notificationPlatformId): ?NotificationPlatform
    {
        return $this->notificationPlatformContract->find($notificationPlatformId);
    }
}
