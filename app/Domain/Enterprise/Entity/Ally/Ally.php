<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally;

use App\Domain\Enterprise\Entity\Ally\Values\AllyCreatedAt;
use App\Domain\Enterprise\Entity\Ally\Values\AllyDeletedAt;
use App\Domain\Enterprise\Entity\Ally\Values\AllyEntityId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyCountryId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Domain\Enterprise\Entity\Ally\Values\AllyName;
use App\Domain\Enterprise\Entity\Ally\Values\AllyDescription;
use App\Domain\Enterprise\Entity\Ally\Values\AllySettings;
use App\Domain\Enterprise\Entity\Ally\Values\AllyRelationships;
use App\Domain\Enterprise\Entity\Ally\Values\AllyUpdatedAt;

final class Ally
{
    private $id;
    private $entityId;
    private $name;
    private $countryId;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?AllyId $id,
        AllyEntityId $entityId,
        AllyName $name,
        AllyCountryId $countryId,
        AllyDescription $description,
        AllySettings $settings,
        ?AllyCreatedAt $createdAt = null,
        ?AllyUpdatedAt $updatedAt = null,
        ?AllyDeletedAt $deletedAt = null,
        ?AllyRelationships $relationships = null
    ) {
        $this->id = $id;
        $this->entityId = $entityId;
        $this->name = $name;
        $this->countryId = $countryId;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?AllyId
    {
        return $this->id;
    }

    public function entityId(): AllyEntityId
    {
        return $this->entityId;
    }

    public function name(): AllyName
    {
        return $this->name;
    }

    public function countryId(): AllyCountryId
    {
        return $this->countryId;
    }

    public function description(): AllyDescription
    {
        return $this->description;
    }

    public function settings(): AllySettings
    {
        return $this->settings;
    }

    public function createdAt(): ?AllyCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?AllyUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?AllyDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?AllyRelationships
    {
        return $this->relationships;
    }
}
