<?php

declare(strict_types=1);

namespace App\Domain\FormationStatus;

use App\Domain\FormationStatus\Values\FormationStatusCreatedAt;
use App\Domain\FormationStatus\Values\FormationStatusDeletedAt;
use App\Domain\FormationStatus\Values\FormationStatusDescription;
use App\Domain\FormationStatus\Values\FormationStatusId;
use App\Domain\FormationStatus\Values\FormationStatusItemId;
use App\Domain\FormationStatus\Values\FormationStatusName;
use App\Domain\FormationStatus\Values\FormationStatusSettings;
use App\Domain\FormationStatus\Values\FormationStatusUpdatedAt;

final class FormationStatus
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
        ?FormationStatusId $id,
        FormationStatusItemId $itemId,
        FormationStatusName $name,
        FormationStatusDescription $description,
        FormationStatusSettings $settings,
        ?FormationStatusCreatedAt $createdAt = null,
        ?FormationStatusUpdatedAt $updatedAt = null,
        ?FormationStatusDeletedAt $deletedAt = null
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

    public function id(): ?FormationStatusId
    {
        return $this->id;
    }

    public function itemId(): FormationStatusItemId
    {
        return $this->itemId;
    }

    public function name(): FormationStatusName
    {
        return $this->name;
    }

    public function description(): FormationStatusDescription
    {
        return $this->description;
    }

    public function settings(): FormationStatusSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?FormationStatusCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FormationStatusUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?FormationStatusDeletedAt
    {
        return $this->deletedAt;
    }
}
