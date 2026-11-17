<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationType;

use App\Application\NotificationTypeUseCase\NotificationTypeSettingUseCase;
use App\Domain\NotificationType\Values\NotificationTypeId;
use App\Http\Requests\NotificationType\NotificationTypeSettingRequest;
use App\Http\Resources\NotificationType\NotificationTypeResource;
use App\Infrastructure\Repositories\NotificationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationTypeRepository;

    public function __construct(NotificationTypeRepository $notificationTypeRepository)
    {
        $this->notificationTypeRepository = $notificationTypeRepository;
    }

    public function __invoke(NotificationTypeSettingRequest $request, int $notificationTypeId)
    {
        try {
            $notificationTypeId = new NotificationTypeId($notificationTypeId ?? null);
            $notificationTypeSettingUseCase = new NotificationTypeSettingUseCase($this->notificationTypeRepository);
            $notificationType = new NotificationTypeResource($notificationTypeSettingUseCase->__invoke($request, $notificationTypeId));
            return $this->successResponse(
                $notificationType,
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
