<?php

declare(strict_types=1);

namespace App\Domain\AcademicLevel;

use App\Domain\AcademicLevel\Values\AcademicLevelCreatedAt;
use App\Domain\AcademicLevel\Values\AcademicLevelDeletedAt;
use App\Domain\AcademicLevel\Values\AcademicLevelDescription;
use App\Domain\AcademicLevel\Values\AcademicLevelId;
use App\Domain\AcademicLevel\Values\AcademicLevelItemId;
use App\Domain\AcademicLevel\Values\AcademicLevelName;
use App\Domain\AcademicLevel\Values\AcademicLevelSettings;
use App\Domain\AcademicLevel\Values\AcademicLevelUpdatedAt;

final class AcademicLevel
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
        ?AcademicLevelId $id,
        AcademicLevelItemId $itemId,
        AcademicLevelName $name,
        AcademicLevelDescription $description,
        AcademicLevelSettings $settings,
        ?AcademicLevelCreatedAt $createdAt = null,
        ?AcademicLevelUpdatedAt $updatedAt = null,
        ?AcademicLevelDeletedAt $deletedAt = null
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

    public function id(): ?AcademicLevelId
    {
        return $this->id;
    }

    public function itemId(): AcademicLevelItemId
    {
        return $this->itemId;
    }

    public function name(): AcademicLevelName
    {
        return $this->name;
    }

    public function description(): AcademicLevelDescription
    {
        return $this->description;
    }

    public function settings(): AcademicLevelSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?AcademicLevelCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AcademicLevelUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?AcademicLevelDeletedAt
    {
        return $this->deletedAt;
    }
}
