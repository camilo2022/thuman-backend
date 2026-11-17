<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocument;

use App\Domain\FiscalDocument\Values\FiscalDocumentCountryId;
use App\Domain\FiscalDocument\Values\FiscalDocumentCreatedAt;
use App\Domain\FiscalDocument\Values\FiscalDocumentDeletedAt;
use App\Domain\FiscalDocument\Values\FiscalDocumentDescription;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;
use App\Domain\FiscalDocument\Values\FiscalDocumentItemId;
use App\Domain\FiscalDocument\Values\FiscalDocumentName;
use App\Domain\FiscalDocument\Values\FiscalDocumentRelationships;
use App\Domain\FiscalDocument\Values\FiscalDocumentSettings;
use App\Domain\FiscalDocument\Values\FiscalDocumentUpdatedAt;

final class FiscalDocument
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $countryId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?FiscalDocumentId $id,
        FiscalDocumentItemId $itemId,
        FiscalDocumentName $name,
        FiscalDocumentDescription $description,
        FiscalDocumentSettings $settings,
        ?FiscalDocumentCountryId $countryId = null,
        ?FiscalDocumentCreatedAt $createdAt = null,
        ?FiscalDocumentUpdatedAt $updatedAt = null,
        ?FiscalDocumentDeletedAt $deletedAt = null,
        ?FiscalDocumentRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->countryId = $countryId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?FiscalDocumentId
    {
        return $this->id;
    }

    public function itemId(): FiscalDocumentItemId
    {
        return $this->itemId;
    }

    public function name(): FiscalDocumentName
    {
        return $this->name;
    }

    public function description(): FiscalDocumentDescription
    {
        return $this->description;
    }

    public function settings(): FiscalDocumentSettings
    {
        return $this->settings;
    }

    public function countryId(): ?FiscalDocumentCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?FiscalDocumentCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FiscalDocumentUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?FiscalDocumentDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?FiscalDocumentRelationships
    {
        return $this->relationships;
    }
}
