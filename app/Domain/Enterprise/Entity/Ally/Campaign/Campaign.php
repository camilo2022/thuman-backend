<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Campaign;

use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignCreatedAt;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignDeletedAt;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignAllyId;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignId;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignName;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignDescription;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignRelationships;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignUpdatedAt;

final class Campaign
{
    private $id;
    private $allyId;
    private $name;
    private $description;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?CampaignId $id,
        CampaignAllyId $allyId,
        CampaignName $name,
        CampaignDescription $description,
        ?CampaignCreatedAt $createdAt = null,
        ?CampaignUpdatedAt $updatedAt = null,
        ?CampaignDeletedAt $deletedAt = null,
        ?CampaignRelationships $relationships = null
    ) {
        $this->id = $id;
        $this->allyId = $allyId;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?CampaignId
    {
        return $this->id;
    }

    public function allyId(): CampaignAllyId
    {
        return $this->allyId;
    }

    public function name(): CampaignName
    {
        return $this->name;
    }

    public function description(): CampaignDescription
    {
        return $this->description;
    }

    public function createdAt(): ?CampaignCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CampaignUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CampaignDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?CampaignRelationships
    {
        return $this->relationships;
    }
}
