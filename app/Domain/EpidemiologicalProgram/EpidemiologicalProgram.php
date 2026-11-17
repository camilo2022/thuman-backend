<?php

declare(strict_types=1);

namespace App\Domain\EpidemiologicalProgram;

use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramCreatedAt;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramDeletedAt;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramDescription;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramItemId;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramName;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramSettings;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramUpdatedAt;

final class EpidemiologicalProgram
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
        ?EpidemiologicalProgramId $id,
        EpidemiologicalProgramItemId $itemId,
        EpidemiologicalProgramName $name,
        EpidemiologicalProgramDescription $description,
        EpidemiologicalProgramSettings $settings,
        ?EpidemiologicalProgramCreatedAt $createdAt = null,
        ?EpidemiologicalProgramUpdatedAt $updatedAt = null,
        ?EpidemiologicalProgramDeletedAt $deletedAt = null
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

    public function id(): ?EpidemiologicalProgramId
    {
        return $this->id;
    }

    public function itemId(): EpidemiologicalProgramItemId
    {
        return $this->itemId;
    }

    public function name(): EpidemiologicalProgramName
    {
        return $this->name;
    }

    public function description(): EpidemiologicalProgramDescription
    {
        return $this->description;
    }

    public function settings(): EpidemiologicalProgramSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?EpidemiologicalProgramCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?EpidemiologicalProgramUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?EpidemiologicalProgramDeletedAt
    {
        return $this->deletedAt;
    }
}
