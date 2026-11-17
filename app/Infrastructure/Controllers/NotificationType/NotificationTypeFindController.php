<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationType;

use App\Application\NotificationTypeUseCase\NotificationTypeFindUseCase;
use App\Domain\NotificationType\Values\NotificationTypeId;
use App\Http\Resources\NotificationType\NotificationTypeResource;
use App\Infrastructure\Repositories\NotificationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationTypeRepository;

    public function __construct(NotificationTypeRepository $notificationTypeRepository)
    {
        $this->notificationTypeRepository = $notificationTypeRepository;
    }

    public function __invoke(int $notificationTypeId)
    {
        try {
            $notificationTypeId = new NotificationTypeId($notificationTypeId ?? null);
            $notificationTypeFindUseCase = new NotificationTypeFindUseCase($this->notificationTypeRepository);
            $notificationType = new NotificationTypeResource($notificationTypeFindUseCase->__invoke($notificationTypeId));
            return $this->successResponse(
                $notificationType,
                'El tipo de notificación fue encontrada exitosamente.',
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
