<?php

declare(strict_types=1);

namespace App\Domain\Gender;

use App\Domain\Gender\Values\GenderCreatedAt;
use App\Domain\Gender\Values\GenderDeletedAt;
use App\Domain\Gender\Values\GenderDescription;
use App\Domain\Gender\Values\GenderId;
use App\Domain\Gender\Values\GenderItemId;
use App\Domain\Gender\Values\GenderName;
use App\Domain\Gender\Values\GenderSettings;
use App\Domain\Gender\Values\GenderUpdatedAt;

final class Gender
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
        ?GenderId $id,
        GenderItemId $itemId,
        GenderName $name,
        GenderDescription $description,
        GenderSettings $settings,
        ?GenderCreatedAt $createdAt = null,
        ?GenderUpdatedAt $updatedAt = null,
        ?GenderDeletedAt $deletedAt = null
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

    public function id(): ?GenderId
    {
        return $this->id;
    }

    public function itemId(): GenderItemId
    {
        return $this->itemId;
    }

    public function name(): GenderName
    {
        return $this->name;
    }

    public function description(): GenderDescription
    {
        return $this->description;
    }

    public function settings(): GenderSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?GenderCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?GenderUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?GenderDeletedAt
    {
        return $this->deletedAt;
    }
}
