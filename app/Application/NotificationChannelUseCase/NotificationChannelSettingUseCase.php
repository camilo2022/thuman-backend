<?php

declare(strict_types=1);

namespace App\Application\NotificationChannelUseCase;

use App\Domain\NotificationChannel\Contracts\NotificationChannelContract;
use App\Domain\NotificationChannel\NotificationChannel;
use App\Domain\NotificationChannel\Values\NotificationChannelDescription;
use App\Domain\NotificationChannel\Values\NotificationChannelId;
use App\Domain\NotificationChannel\Values\NotificationChannelItemId;
use App\Domain\NotificationChannel\Values\NotificationChannelName;
use App\Domain\NotificationChannel\Values\NotificationChannelSettings;
use App\Http\Requests\NotificationChannel\NotificationChannelSettingRequest;

final class NotificationChannelSettingUseCase
{
    private $notificationChannelContract;

    public function __construct(NotificationChannelContract $notificationChannelContract)
    {
        $this->notificationChannelContract = $notificationChannelContract;
    }

    public function __invoke(NotificationChannelSettingRequest $request, NotificationChannelId $notificationChannelId): NotificationChannel
    {
        return $this->notificationChannelContract->update(
            new NotificationChannel(
                new NotificationChannelId($notificationChannelId->value()),
                new NotificationChannelItemId($request->integer('item_id')),
                new NotificationChannelName($request->input('name')),
                new NotificationChannelDescription($request->input('description')),
                new NotificationChannelSettings((object) $request->input('settings'))
            ),
            $notificationChannelId
        );
    }
}
