<?php

declare(strict_types=1);

namespace App\Domain\CertificateType;

use App\Domain\CertificateType\Values\CertificateTypeCreatedAt;
use App\Domain\CertificateType\Values\CertificateTypeDeletedAt;
use App\Domain\CertificateType\Values\CertificateTypeDescription;
use App\Domain\CertificateType\Values\CertificateTypeId;
use App\Domain\CertificateType\Values\CertificateTypeItemId;
use App\Domain\CertificateType\Values\CertificateTypeName;
use App\Domain\CertificateType\Values\CertificateTypeRelationships;
use App\Domain\CertificateType\Values\CertificateTypeSettings;
use App\Domain\CertificateType\Values\CertificateTypeUpdatedAt;

final class CertificateType
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?CertificateTypeId $id,
        CertificateTypeItemId $itemId,
        CertificateTypeName $name,
        CertificateTypeDescription $description,
        CertificateTypeSettings $settings,
        ?CertificateTypeCreatedAt $createdAt = null,
        ?CertificateTypeUpdatedAt $updatedAt = null,
        ?CertificateTypeDeletedAt $deletedAt = null,
        ?CertificateTypeRelationships $relationships = null
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
        $this->relationships = $relationships;
    }

    public function id(): ?CertificateTypeId
    {
        return $this->id;
    }

    public function itemId(): CertificateTypeItemId
    {
        return $this->itemId;
    }

    public function name(): CertificateTypeName
    {
        return $this->name;
    }

    public function description(): CertificateTypeDescription
    {
        return $this->description;
    }

    public function settings(): CertificateTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?CertificateTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CertificateTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CertificateTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?CertificateTypeRelationships
    {
        return $this->relationships;
    }
}
