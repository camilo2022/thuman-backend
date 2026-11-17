<?php

declare(strict_types=1);

namespace App\Domain\NotificationType\Contracts;

use App\Domain\NotificationType\NotificationType;
use App\Domain\NotificationType\Values\NotificationTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(NotificationTypeId $notificationTypeId): ?NotificationType;

    public function update(NotificationType $notificationType, NotificationTypeId $notificationTypeId): NotificationType;
}
