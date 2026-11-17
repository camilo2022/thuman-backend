<?php

declare(strict_types=1);

namespace App\Application\NotificationTypeUseCase;

use App\Domain\NotificationType\NotificationType;
use App\Domain\NotificationType\Contracts\NotificationTypeContract;
use App\Domain\NotificationType\Values\NotificationTypeId;

final class NotificationTypeFindUseCase
{
    private $notificationTypeContract;

    public function __construct(NotificationTypeContract $notificationTypeContract)
    {
        $this->notificationTypeContract = $notificationTypeContract;
    }

    public function __invoke(NotificationTypeId $notificationTypeId): ?NotificationType
    {
        return $this->notificationTypeContract->find($notificationTypeId);
    }
}
