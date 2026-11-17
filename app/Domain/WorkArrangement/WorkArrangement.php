<?php

declare(strict_types=1);

namespace App\Domain\WorkArrangement;

use App\Domain\WorkArrangement\Values\WorkArrangementCreatedAt;
use App\Domain\WorkArrangement\Values\WorkArrangementDeletedAt;
use App\Domain\WorkArrangement\Values\WorkArrangementDescription;
use App\Domain\WorkArrangement\Values\WorkArrangementId;
use App\Domain\WorkArrangement\Values\WorkArrangementItemId;
use App\Domain\WorkArrangement\Values\WorkArrangementName;
use App\Domain\WorkArrangement\Values\WorkArrangementSettings;
use App\Domain\WorkArrangement\Values\WorkArrangementUpdatedAt;

final class WorkArrangement
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
        ?WorkArrangementId $id,
        WorkArrangementItemId $itemId,
        WorkArrangementName $name,
        WorkArrangementDescription $description,
        WorkArrangementSettings $settings,
        ?WorkArrangementCreatedAt $createdAt = null,
        ?WorkArrangementUpdatedAt $updatedAt = null,
        ?WorkArrangementDeletedAt $deletedAt = null
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

    public function id(): ?WorkArrangementId
    {
        return $this->id;
    }

    public function itemId(): WorkArrangementItemId
    {
        return $this->itemId;
    }

    public function name(): WorkArrangementName
    {
        return $this->name;
    }

    public function description(): WorkArrangementDescription
    {
        return $this->description;
    }

    public function settings(): WorkArrangementSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?WorkArrangementCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?WorkArrangementUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?WorkArrangementDeletedAt
    {
        return $this->deletedAt;
    }
}
