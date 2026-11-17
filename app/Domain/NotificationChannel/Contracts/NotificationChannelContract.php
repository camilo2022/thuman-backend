<?php

declare(strict_types=1);

namespace App\Domain\NotificationChannel\Contracts;

use App\Domain\NotificationChannel\NotificationChannel;
use App\Domain\NotificationChannel\Values\NotificationChannelId;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationChannelContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(NotificationChannelId $notificationChannelId): ?NotificationChannel;

    public function update(NotificationChannel $notificationChannel, NotificationChannelId $notificationChannelId): NotificationChannel;
}
