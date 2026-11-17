<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationChannel;

use App\Application\NotificationChannelUseCase\NotificationChannelAllUseCase;
use App\Http\Requests\NotificationChannel\NotificationChannelAllRequest;
use App\Http\Resources\NotificationChannel\NotificationChannelCollection;
use App\Infrastructure\Repositories\NotificationChannelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationChannelAllController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationChannelRepository;

    public function __construct(NotificationChannelRepository $notificationChannelRepository)
    {
        $this->notificationChannelRepository = $notificationChannelRepository;
    }

    public function __invoke(NotificationChannelAllRequest $request)
    {
        try {
            $notificationChannelAllUseCase = new NotificationChannelAllUseCase($this->notificationChannelRepository);
            $notificationChannels = new NotificationChannelCollection($notificationChannelAllUseCase->__invoke($request));
            return $this->successResponse(
                $notificationChannels,
                'Los canales de notificación consultados exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
