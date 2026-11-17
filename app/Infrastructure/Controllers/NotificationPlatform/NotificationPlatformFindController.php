<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationPlatform;

use App\Application\NotificationPlatformUseCase\NotificationPlatformFindUseCase;
use App\Domain\NotificationPlatform\Values\NotificationPlatformId;
use App\Http\Resources\NotificationPlatform\NotificationPlatformResource;
use App\Infrastructure\Repositories\NotificationPlatformRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationPlatformFindController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationPlatformRepository;

    public function __construct(NotificationPlatformRepository $notificationPlatformRepository)
    {
        $this->notificationPlatformRepository = $notificationPlatformRepository;
    }

    public function __invoke(int $notificationPlatformId)
    {
        try {
            $notificationPlatformId = new NotificationPlatformId($notificationPlatformId ?? null);
            $notificationPlatformFindUseCase = new NotificationPlatformFindUseCase($this->notificationPlatformRepository);
            $notificationPlatform = new NotificationPlatformResource($notificationPlatformFindUseCase->__invoke($notificationPlatformId));
            return $this->successResponse(
                $notificationPlatform,
                'La plataforma de notificación fue encontrada exitosamente.',
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
