<?php

declare(strict_types=1);

namespace App\Domain\NotificationPlatform;

use App\Domain\NotificationPlatform\Values\NotificationPlatformCreatedAt;
use App\Domain\NotificationPlatform\Values\NotificationPlatformDeletedAt;
use App\Domain\NotificationPlatform\Values\NotificationPlatformDescription;
use App\Domain\NotificationPlatform\Values\NotificationPlatformId;
use App\Domain\NotificationPlatform\Values\NotificationPlatformItemId;
use App\Domain\NotificationPlatform\Values\NotificationPlatformName;
use App\Domain\NotificationPlatform\Values\NotificationPlatformSettings;
use App\Domain\NotificationPlatform\Values\NotificationPlatformUpdatedAt;

final class NotificationPlatform
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
        ?NotificationPlatformId $id,
        NotificationPlatformItemId $itemId,
        NotificationPlatformName $name,
        NotificationPlatformDescription $description,
        NotificationPlatformSettings $settings,
        ?NotificationPlatformCreatedAt $createdAt = null,
        ?NotificationPlatformUpdatedAt $updatedAt = null,
        ?NotificationPlatformDeletedAt $deletedAt = null
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

    public function id(): ?NotificationPlatformId
    {
        return $this->id;
    }

    public function itemId(): NotificationPlatformItemId
    {
        return $this->itemId;
    }

    public function name(): NotificationPlatformName
    {
        return $this->name;
    }

    public function description(): NotificationPlatformDescription
    {
        return $this->description;
    }

    public function settings(): NotificationPlatformSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?NotificationPlatformCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?NotificationPlatformUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?NotificationPlatformDeletedAt
    {
        return $this->deletedAt;
    }
}
