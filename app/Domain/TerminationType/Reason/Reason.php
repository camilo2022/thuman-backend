<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Reason;

use App\Domain\TerminationType\Reason\Values\ReasonCreatedAt;
use App\Domain\TerminationType\Reason\Values\ReasonDeletedAt;
use App\Domain\TerminationType\Reason\Values\ReasonDescription;
use App\Domain\TerminationType\Reason\Values\ReasonId;
use App\Domain\TerminationType\Reason\Values\ReasonItemId;
use App\Domain\TerminationType\Reason\Values\ReasonName;
use App\Domain\TerminationType\Reason\Values\ReasonTerminationTypeId;
use App\Domain\TerminationType\Reason\Values\ReasonRelationships;
use App\Domain\TerminationType\Reason\Values\ReasonSettings;
use App\Domain\TerminationType\Reason\Values\ReasonUpdatedAt;

final class Reason
{
    private $id;
    private $terminationTypeId;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?ReasonId $id,
        ReasonTerminationTypeId $terminationTypeId,
        ReasonItemId $itemId,
        ReasonName $name,
        ReasonDescription $description,
        ReasonSettings $settings,
        ?ReasonCreatedAt $createdAt = null,
        ?ReasonUpdatedAt $updatedAt = null,
        ?ReasonDeletedAt $deletedAt = null,
        ?ReasonRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->terminationTypeId = $terminationTypeId;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ReasonId
    {
        return $this->id;
    }

    public function terminationTypeId(): ReasonTerminationTypeId
    {
        return $this->terminationTypeId;
    }

    public function itemId(): ReasonItemId
    {
        return $this->itemId;
    }

    public function name(): ReasonName
    {
        return $this->name;
    }

    public function description(): ReasonDescription
    {
        return $this->description;
    }

    public function settings(): ReasonSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?ReasonCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ReasonUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ReasonDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?ReasonRelationships
    {
        return $this->relationships;
    }
}
