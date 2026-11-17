<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationChannel;

use App\Application\NotificationChannelUseCase\NotificationChannelFindUseCase;
use App\Domain\NotificationChannel\Values\NotificationChannelId;
use App\Http\Resources\NotificationChannel\NotificationChannelResource;
use App\Infrastructure\Repositories\NotificationChannelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationChannelFindController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationChannelRepository;

    public function __construct(NotificationChannelRepository $notificationChannelRepository)
    {
        $this->notificationChannelRepository = $notificationChannelRepository;
    }

    public function __invoke(int $notificationChannelId)
    {
        try {
            $notificationChannelId = new NotificationChannelId($notificationChannelId ?? null);
            $notificationChannelFindUseCase = new NotificationChannelFindUseCase($this->notificationChannelRepository);
            $notificationChannel = new NotificationChannelResource($notificationChannelFindUseCase->__invoke($notificationChannelId));
            return $this->successResponse(
                $notificationChannel,
                'El canal de notificación fue encontrada exitosamente.',
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
