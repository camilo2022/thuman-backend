<?php

declare(strict_types=1);

namespace App\Domain\ServiceProvider;

use App\Domain\ServiceProvider\Values\ServiceProviderCountryId;
use App\Domain\ServiceProvider\Values\ServiceProviderServiceTypeId;
use App\Domain\ServiceProvider\Values\ServiceProviderCreatedAt;
use App\Domain\ServiceProvider\Values\ServiceProviderDeletedAt;
use App\Domain\ServiceProvider\Values\ServiceProviderDescription;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Domain\ServiceProvider\Values\ServiceProviderItemId;
use App\Domain\ServiceProvider\Values\ServiceProviderName;
use App\Domain\ServiceProvider\Values\ServiceProviderRelationships;
use App\Domain\ServiceProvider\Values\ServiceProviderSettings;
use App\Domain\ServiceProvider\Values\ServiceProviderUpdatedAt;

final class ServiceProvider
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $serviceTypeId;
    private $countryId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?ServiceProviderId $id,
        ServiceProviderItemId $itemId,
        ServiceProviderName $name,
        ServiceProviderDescription $description,
        ServiceProviderSettings $settings,
        ?ServiceProviderServiceTypeId $serviceTypeId = null,
        ?ServiceProviderCountryId $countryId = null,
        ?ServiceProviderCreatedAt $createdAt = null,
        ?ServiceProviderUpdatedAt $updatedAt = null,
        ?ServiceProviderDeletedAt $deletedAt = null,
        ?ServiceProviderRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->serviceTypeId = $serviceTypeId;
        $this->countryId = $countryId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ServiceProviderId
    {
        return $this->id;
    }

    public function itemId(): ServiceProviderItemId
    {
        return $this->itemId;
    }

    public function name(): ServiceProviderName
    {
        return $this->name;
    }

    public function description(): ServiceProviderDescription
    {
        return $this->description;
    }

    public function settings(): ServiceProviderSettings
    {
        return $this->settings;
    }

    public function serviceTypeId(): ?ServiceProviderServiceTypeId
    {
        return $this->serviceTypeId;
    }

    public function countryId(): ?ServiceProviderCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?ServiceProviderCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ServiceProviderUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ServiceProviderDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?ServiceProviderRelationships
    {
        return $this->relationships;
    }
}
