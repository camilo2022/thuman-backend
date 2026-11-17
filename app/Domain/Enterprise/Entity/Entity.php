<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity;

use App\Domain\Enterprise\Entity\Values\EntityAboutUs;
use App\Domain\Enterprise\Entity\Values\EntityEntityTypeId;
use App\Domain\Enterprise\Entity\Values\EntityCountryId;
use App\Domain\Enterprise\Entity\Values\EntityCreatedAt;
use App\Domain\Enterprise\Entity\Values\EntityDeletedAt;
use App\Domain\Enterprise\Entity\Values\EntityDocumentNumber;
use App\Domain\Enterprise\Entity\Values\EntityDocumentTypeId;
use App\Domain\Enterprise\Entity\Values\EntityEnterpriseId;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Domain\Enterprise\Entity\Values\EntityMission;
use App\Domain\Enterprise\Entity\Values\EntityPersonId;
use App\Domain\Enterprise\Entity\Values\EntityName;
use App\Domain\Enterprise\Entity\Values\EntityRelationships;
use App\Domain\Enterprise\Entity\Values\EntitySettings;
use App\Domain\Enterprise\Entity\Values\EntityUpdatedAt;
use App\Domain\Enterprise\Entity\Values\EntityVision;

final class Entity
{
    private $id;
    private $enterpriseId;
    private $entityTypeId;
    private $name;
    private $countryId;
    private $documentTypeId;
    private $documentNumber;
    private $personId;
    private $aboutUs;
    private $mission;
    private $vision;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?EntityId $id,
        EntityEnterpriseId $enterpriseId,
        EntityEntityTypeId $entityTypeId,
        EntityName $name,
        EntityCountryId $countryId,
        EntityDocumentTypeId $documentTypeId,
        EntityDocumentNumber $documentNumber,
        EntityPersonId $personId,
        ?EntityAboutUs $aboutUs,
        ?EntityMission $mission,
        ?EntityVision $vision,
        EntitySettings $settings,
        ?EntityCreatedAt $createdAt = null,
        ?EntityUpdatedAt $updatedAt = null,
        ?EntityDeletedAt $deletedAt = null,
        ?EntityRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->enterpriseId = $enterpriseId;
        $this->entityTypeId = $entityTypeId;
        $this->name = $name;
        $this->countryId = $countryId;
        $this->documentTypeId = $documentTypeId;
        $this->documentNumber = $documentNumber;
        $this->personId = $personId;
        $this->aboutUs = $aboutUs;
        $this->mission = $mission;
        $this->vision = $vision;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?EntityId
    {
        return $this->id;
    }

    public function enterpriseId(): EntityEnterpriseId
    {
        return $this->enterpriseId;
    }

    public function entityTypeId(): EntityEntityTypeId
    {
        return $this->entityTypeId;
    }

    public function name(): EntityName
    {
        return $this->name;
    }

    public function countryId(): EntityCountryId
    {
        return $this->countryId;
    }

    public function documentTypeId(): EntityDocumentTypeId
    {
        return $this->documentTypeId;
    }

    public function documentNumber(): EntityDocumentNumber
    {
        return $this->documentNumber;
    }

    public function personId(): EntityPersonId
    {
        return $this->personId;
    }

    public function aboutUs(): EntityAboutUs
    {
        return $this->aboutUs;
    }

    public function mission(): EntityMission
    {
        return $this->mission;
    }

    public function vision(): EntityVision
    {
        return $this->vision;
    }

    public function settings(): EntitySettings
    {
        return $this->settings;
    }

    public function createdAt(): ?EntityCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?EntityUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?EntityDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?EntityRelationships
    {
        return $this->relationships;
    }
}
