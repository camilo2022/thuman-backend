<?php

declare(strict_types=1);

namespace App\Application\NotificationChannelUseCase;

use App\Domain\NotificationChannel\Contracts\NotificationChannelContract;
use App\Http\Requests\NotificationChannel\NotificationChannelAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class NotificationChannelAllUseCase
{
    private $notificationChannelContract;

    public function __construct(NotificationChannelContract $notificationChannelContract)
    {
        $this->notificationChannelContract = $notificationChannelContract;
    }

    public function __invoke(NotificationChannelAllRequest $request): LengthAwarePaginator
    {
        return $this->notificationChannelContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
