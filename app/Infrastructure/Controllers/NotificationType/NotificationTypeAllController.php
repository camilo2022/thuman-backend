<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\NotificationType;

use App\Application\NotificationTypeUseCase\NotificationTypeAllUseCase;
use App\Http\Requests\NotificationType\NotificationTypeAllRequest;
use App\Http\Resources\NotificationType\NotificationTypeCollection;
use App\Infrastructure\Repositories\NotificationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class NotificationTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $notificationTypeRepository;

    public function __construct(NotificationTypeRepository $notificationTypeRepository)
    {
        $this->notificationTypeRepository = $notificationTypeRepository;
    }

    public function __invoke(NotificationTypeAllRequest $request)
    {
        try {
            $notificationTypeAllUseCase = new NotificationTypeAllUseCase($this->notificationTypeRepository);
            $notificationTypes = new NotificationTypeCollection($notificationTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $notificationTypes,
                'Los tipos de notificación consultados exitosamente.',
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
