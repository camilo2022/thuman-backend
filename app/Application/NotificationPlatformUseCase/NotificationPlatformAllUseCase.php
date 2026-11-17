<?php

declare(strict_types=1);

namespace App\Application\NotificationPlatformUseCase;

use App\Domain\NotificationPlatform\Contracts\NotificationPlatformContract;
use App\Http\Requests\NotificationPlatform\NotificationPlatformAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class NotificationPlatformAllUseCase
{
    private $notificationPlatformContract;

    public function __construct(NotificationPlatformContract $notificationPlatformContract)
    {
        $this->notificationPlatformContract = $notificationPlatformContract;
    }

    public function __invoke(NotificationPlatformAllRequest $request): LengthAwarePaginator
    {
        return $this->notificationPlatformContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
