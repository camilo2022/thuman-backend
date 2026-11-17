<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationChannel;

use App\Application\NotificationChannelUseCase\NotificationChannelSettingUseCase;
use App\Domain\NotificationChannel\Values\NotificationChannelId;
use App\Http\Requests\NotificationChannel\NotificationChannelSettingRequest;
use App\Http\Resources\NotificationChannel\NotificationChannelResource;
use App\Infrastructure\Repositories\NotificationChannelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationChannelSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationChannelRepository;

    public function __construct(NotificationChannelRepository $notificationChannelRepository)
    {
        $this->notificationChannelRepository = $notificationChannelRepository;
    }

    public function __invoke(NotificationChannelSettingRequest $request, int $notificationChannelId)
    {
        try {
            $notificationChannelId = new NotificationChannelId($notificationChannelId ?? null);
            $notificationChannelSettingUseCase = new NotificationChannelSettingUseCase($this->notificationChannelRepository);
            $notificationChannel = new NotificationChannelResource($notificationChannelSettingUseCase->__invoke($request, $notificationChannelId));
            return $this->successResponse(
                $notificationChannel,
                'La configuración del canal de notificación fue realizada exitosamente.',
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
