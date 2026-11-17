<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationPlatform;

use App\Application\NotificationPlatformUseCase\NotificationPlatformAllUseCase;
use App\Http\Requests\NotificationPlatform\NotificationPlatformAllRequest;
use App\Http\Resources\NotificationPlatform\NotificationPlatformCollection;
use App\Infrastructure\Repositories\NotificationPlatformRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationPlatformAllController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationPlatformRepository;

    public function __construct(NotificationPlatformRepository $notificationPlatformRepository)
    {
        $this->notificationPlatformRepository = $notificationPlatformRepository;
    }

    public function __invoke(NotificationPlatformAllRequest $request)
    {
        try {
            $notificationPlatformAllUseCase = new NotificationPlatformAllUseCase($this->notificationPlatformRepository);
            $notificationPlatforms = new NotificationPlatformCollection($notificationPlatformAllUseCase->__invoke($request));
            return $this->successResponse(
                $notificationPlatforms,
                'Las plataformas de notificación consultados exitosamente.',
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
