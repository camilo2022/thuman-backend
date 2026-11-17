<?php

declare(strict_types=1);

namespace App\Domain\NotificationChannel;

use App\Domain\NotificationChannel\Values\NotificationChannelCreatedAt;
use App\Domain\NotificationChannel\Values\NotificationChannelDeletedAt;
use App\Domain\NotificationChannel\Values\NotificationChannelDescription;
use App\Domain\NotificationChannel\Values\NotificationChannelId;
use App\Domain\NotificationChannel\Values\NotificationChannelItemId;
use App\Domain\NotificationChannel\Values\NotificationChannelName;
use App\Domain\NotificationChannel\Values\NotificationChannelSettings;
use App\Domain\NotificationChannel\Values\NotificationChannelUpdatedAt;

final class NotificationChannel
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?NotificationChannelId $id,
        NotificationChannelItemId $itemId,
        NotificationChannelName $name,
        NotificationChannelDescription $description,
        NotificationChannelSettings $settings,
        ?NotificationChannelCreatedAt $createdAt = null,
        ?NotificationChannelUpdatedAt $updatedAt = null,
        ?NotificationChannelDeletedAt $deletedAt = null
    )
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function id(): ?NotificationChannelId
    {
        return $this->id;
    }

    public function itemId(): NotificationChannelItemId
    {
        return $this->itemId;
    }

    public function name(): NotificationChannelName
    {
        return $this->name;
    }

    public function description(): NotificationChannelDescription
    {
        return $this->description;
    }

    public function settings(): NotificationChannelSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?NotificationChannelCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?NotificationChannelUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?NotificationChannelDeletedAt
    {
        return $this->deletedAt;
    }
}
