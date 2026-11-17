<?php

declare(strict_types=1);

namespace App\Domain\MaritalStatus;

use App\Domain\MaritalStatus\Values\MaritalStatusCreatedAt;
use App\Domain\MaritalStatus\Values\MaritalStatusDeletedAt;
use App\Domain\MaritalStatus\Values\MaritalStatusDescription;
use App\Domain\MaritalStatus\Values\MaritalStatusId;
use App\Domain\MaritalStatus\Values\MaritalStatusItemId;
use App\Domain\MaritalStatus\Values\MaritalStatusName;
use App\Domain\MaritalStatus\Values\MaritalStatusSettings;
use App\Domain\MaritalStatus\Values\MaritalStatusUpdatedAt;

final class MaritalStatus
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
        ?MaritalStatusId $id,
        MaritalStatusItemId $itemId,
        MaritalStatusName $name,
        MaritalStatusDescription $description,
        MaritalStatusSettings $settings,
        ?MaritalStatusCreatedAt $createdAt = null,
        ?MaritalStatusUpdatedAt $updatedAt = null,
        ?MaritalStatusDeletedAt $deletedAt = null
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

    public function id(): ?MaritalStatusId
    {
        return $this->id;
    }

    public function itemId(): MaritalStatusItemId
    {
        return $this->itemId;
    }

    public function name(): MaritalStatusName
    {
        return $this->name;
    }

    public function description(): MaritalStatusDescription
    {
        return $this->description;
    }

    public function settings(): MaritalStatusSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?MaritalStatusCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?MaritalStatusUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?MaritalStatusDeletedAt
    {
        return $this->deletedAt;
    }
}
