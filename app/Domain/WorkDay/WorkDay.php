<?php

declare(strict_types=1);

namespace App\Domain\WorkDay;

use App\Domain\WorkDay\Values\WorkDayCreatedAt;
use App\Domain\WorkDay\Values\WorkDayDeletedAt;
use App\Domain\WorkDay\Values\WorkDayDescription;
use App\Domain\WorkDay\Values\WorkDayId;
use App\Domain\WorkDay\Values\WorkDayItemId;
use App\Domain\WorkDay\Values\WorkDayName;
use App\Domain\WorkDay\Values\WorkDaySettings;
use App\Domain\WorkDay\Values\WorkDayUpdatedAt;

final class WorkDay
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
        ?WorkDayId $id,
        WorkDayItemId $itemId,
        WorkDayName $name,
        WorkDayDescription $description,
        WorkDaySettings $settings,
        ?WorkDayCreatedAt $createdAt = null,
        ?WorkDayUpdatedAt $updatedAt = null,
        ?WorkDayDeletedAt $deletedAt = null
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

    public function id(): ?WorkDayId
    {
        return $this->id;
    }

    public function itemId(): WorkDayItemId
    {
        return $this->itemId;
    }

    public function name(): WorkDayName
    {
        return $this->name;
    }

    public function description(): WorkDayDescription
    {
        return $this->description;
    }

    public function settings(): WorkDaySettings
    {
        return $this->settings;
    }

    public function createdAt(): ?WorkDayCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?WorkDayUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?WorkDayDeletedAt
    {
        return $this->deletedAt;
    }
}
