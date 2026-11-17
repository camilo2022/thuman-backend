<?php

declare(strict_types=1);

namespace App\Application\NotificationTypeUseCase;

use App\Domain\NotificationType\Contracts\NotificationTypeContract;
use App\Domain\NotificationType\NotificationType;
use App\Domain\NotificationType\Values\NotificationTypeDescription;
use App\Domain\NotificationType\Values\NotificationTypeId;
use App\Domain\NotificationType\Values\NotificationTypeItemId;
use App\Domain\NotificationType\Values\NotificationTypeName;
use App\Domain\NotificationType\Values\NotificationTypeSettings;
use App\Http\Requests\NotificationType\NotificationTypeSettingRequest;

final class NotificationTypeSettingUseCase
{
    private $notificationTypeContract;

    public function __construct(NotificationTypeContract $notificationTypeContract)
    {
        $this->notificationTypeContract = $notificationTypeContract;
    }

    public function __invoke(NotificationTypeSettingRequest $request, NotificationTypeId $notificationTypeId): NotificationType
    {
        return $this->notificationTypeContract->update(
            new NotificationType(
                new NotificationTypeId($notificationTypeId->value()),
                new NotificationTypeItemId($request->integer('item_id')),
                new NotificationTypeName($request->input('name')),
                new NotificationTypeDescription($request->input('description')),
                new NotificationTypeSettings((object) $request->input('settings'))
            ),
            $notificationTypeId
        );
    }
}
