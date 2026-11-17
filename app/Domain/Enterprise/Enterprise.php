<?php

declare(strict_types=1);

namespace App\Domain\Enterprise;

use App\Domain\Enterprise\Values\EnterpriseCreatedAt;
use App\Domain\Enterprise\Values\EnterpriseDeletedAt;
use App\Domain\Enterprise\Values\EnterpriseDescription;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Domain\Enterprise\Values\EnterpriseName;
use App\Domain\Enterprise\Values\EnterpriseUpdatedAt;

final class Enterprise
{
    private $id;
    private $name;
    private $description;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $logo;

    public function __construct(
        ?EnterpriseId $id,
        EnterpriseName $name,
        EnterpriseDescription $description,
        ?EnterpriseCreatedAt $createdAt = null,
        ?EnterpriseUpdatedAt $updatedAt = null,
        ?EnterpriseDeletedAt $deletedAt = null,
        ?Logo $logo = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->logo = $logo;
    }

    public function id(): ?EnterpriseId
    {
        return $this->id;
    }

    public function name(): EnterpriseName
    {
        return $this->name;
    }

    public function description(): EnterpriseDescription
    {
        return $this->description;
    }

    public function createdAt(): ?EnterpriseCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?EnterpriseUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?EnterpriseDeletedAt
    {
        return $this->deletedAt;
    }

    public function logo(): ?Logo
    {
        return $this->logo;
    }
}
