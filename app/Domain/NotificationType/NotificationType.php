<?php

declare(strict_types=1);

namespace App\Domain\NotificationType;

use App\Domain\NotificationType\Values\NotificationTypeCreatedAt;
use App\Domain\NotificationType\Values\NotificationTypeDeletedAt;
use App\Domain\NotificationType\Values\NotificationTypeDescription;
use App\Domain\NotificationType\Values\NotificationTypeId;
use App\Domain\NotificationType\Values\NotificationTypeItemId;
use App\Domain\NotificationType\Values\NotificationTypeName;
use App\Domain\NotificationType\Values\NotificationTypeSettings;
use App\Domain\NotificationType\Values\NotificationTypeUpdatedAt;

final class NotificationType
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
        ?NotificationTypeId $id,
        NotificationTypeItemId $itemId,
        NotificationTypeName $name,
        NotificationTypeDescription $description,
        NotificationTypeSettings $settings,
        ?NotificationTypeCreatedAt $createdAt = null,
        ?NotificationTypeUpdatedAt $updatedAt = null,
        ?NotificationTypeDeletedAt $deletedAt = null
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

    public function id(): ?NotificationTypeId
    {
        return $this->id;
    }

    public function itemId(): NotificationTypeItemId
    {
        return $this->itemId;
    }

    public function name(): NotificationTypeName
    {
        return $this->name;
    }

    public function description(): NotificationTypeDescription
    {
        return $this->description;
    }

    public function settings(): NotificationTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?NotificationTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?NotificationTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?NotificationTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
