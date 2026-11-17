<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Headquarter;

use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterAddress;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterCityId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterCreatedAt;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterDeletedAt;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterEntityId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterId;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterName;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterRelationships;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterUpdatedAt;

final class Headquarter
{
    private $id;
    private $entityId;
    private $name;
    private $cityId;
    private $address;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?HeadquarterId $id,
        HeadquarterEntityId $entityId,
        HeadquarterName $name,
        HeadquarterCityId $cityId,
        HeadquarterAddress $address,
        ?HeadquarterCreatedAt $createdAt = null,
        ?HeadquarterUpdatedAt $updatedAt = null,
        ?HeadquarterDeletedAt $deletedAt = null,
        ?HeadquarterRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->entityId = $entityId;
        $this->name = $name;
        $this->cityId = $cityId;
        $this->address = $address;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?HeadquarterId
    {
        return $this->id;
    }

    public function entityId(): HeadquarterEntityId
    {
        return $this->entityId;
    }

    public function name(): HeadquarterName
    {
        return $this->name;
    }

    public function cityId(): HeadquarterCityId
    {
        return $this->cityId;
    }

    public function address(): HeadquarterAddress
    {
        return $this->address;
    }

    public function createdAt(): ?HeadquarterCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?HeadquarterUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?HeadquarterDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?HeadquarterRelationships
    {
        return $this->relationships;
    }
}
