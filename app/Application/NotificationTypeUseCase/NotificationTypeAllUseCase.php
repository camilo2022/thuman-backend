<?php

declare(strict_types=1);

namespace App\Application\NotificationTypeUseCase;

use App\Domain\NotificationType\Contracts\NotificationTypeContract;
use App\Http\Requests\NotificationType\NotificationTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class NotificationTypeAllUseCase
{
    private $notificationTypeContract;

    public function __construct(NotificationTypeContract $notificationTypeContract)
    {
        $this->notificationTypeContract = $notificationTypeContract;
    }

    public function __invoke(NotificationTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->notificationTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
