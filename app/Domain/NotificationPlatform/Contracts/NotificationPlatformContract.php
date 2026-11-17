<?php

declare(strict_types=1);

namespace App\Domain\NotificationPlatform\Contracts;

use App\Domain\NotificationPlatform\NotificationPlatform;
use App\Domain\NotificationPlatform\Values\NotificationPlatformId;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationPlatformContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(NotificationPlatformId $notificationPlatformId): ?NotificationPlatform;
}
