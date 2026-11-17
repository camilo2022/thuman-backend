<?php

declare(strict_types=1);

namespace App\Domain\HiringReason;

use App\Domain\HiringReason\Values\HiringReasonCreatedAt;
use App\Domain\HiringReason\Values\HiringReasonDeletedAt;
use App\Domain\HiringReason\Values\HiringReasonDescription;
use App\Domain\HiringReason\Values\HiringReasonId;
use App\Domain\HiringReason\Values\HiringReasonItemId;
use App\Domain\HiringReason\Values\HiringReasonName;
use App\Domain\HiringReason\Values\HiringReasonSettings;
use App\Domain\HiringReason\Values\HiringReasonUpdatedAt;

final class HiringReason
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
        ?HiringReasonId $id,
        HiringReasonItemId $itemId,
        HiringReasonName $name,
        HiringReasonDescription $description,
        HiringReasonSettings $settings,
        ?HiringReasonCreatedAt $createdAt = null,
        ?HiringReasonUpdatedAt $updatedAt = null,
        ?HiringReasonDeletedAt $deletedAt = null
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

    public function id(): ?HiringReasonId
    {
        return $this->id;
    }

    public function itemId(): HiringReasonItemId
    {
        return $this->itemId;
    }

    public function name(): HiringReasonName
    {
        return $this->name;
    }

    public function description(): HiringReasonDescription
    {
        return $this->description;
    }

    public function settings(): HiringReasonSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?HiringReasonCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?HiringReasonUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?HiringReasonDeletedAt
    {
        return $this->deletedAt;
    }
}
