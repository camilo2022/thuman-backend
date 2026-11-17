<?php

declare(strict_types=1);

namespace App\Domain\Sex;

use App\Domain\Sex\Values\SexCreatedAt;
use App\Domain\Sex\Values\SexDeletedAt;
use App\Domain\Sex\Values\SexDescription;
use App\Domain\Sex\Values\SexId;
use App\Domain\Sex\Values\SexItemId;
use App\Domain\Sex\Values\SexName;
use App\Domain\Sex\Values\SexSettings;
use App\Domain\Sex\Values\SexUpdatedAt;

final class Sex
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
        ?SexId $id,
        SexItemId $itemId,
        SexName $name,
        SexDescription $description,
        SexSettings $settings,
        ?SexCreatedAt $createdAt = null,
        ?SexUpdatedAt $updatedAt = null,
        ?SexDeletedAt $deletedAt = null
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

    public function id(): ?SexId
    {
        return $this->id;
    }

    public function itemId(): SexItemId
    {
        return $this->itemId;
    }

    public function name(): SexName
    {
        return $this->name;
    }

    public function description(): SexDescription
    {
        return $this->description;
    }

    public function settings(): SexSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?SexCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SexUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?SexDeletedAt
    {
        return $this->deletedAt;
    }
}
