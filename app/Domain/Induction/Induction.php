<?php

declare(strict_types=1);

namespace App\Domain\Induction;

use App\Domain\Induction\Values\InductionAssignableId;
use App\Domain\Induction\Values\InductionAssignableType;
use App\Domain\Induction\Values\InductionCreatedAt;
use App\Domain\Induction\Values\InductionDeletedAt;
use App\Domain\Induction\Values\InductionDescription;
use App\Domain\Induction\Values\InductionId;
use App\Domain\Induction\Values\InductionModelId;
use App\Domain\Induction\Values\InductionModelType;
use App\Domain\Induction\Values\InductionName;
use App\Domain\Induction\Values\InductionRelationships;
use App\Domain\Induction\Values\InductionUpdatedAt;

final class Induction
{
    private $id;
    private $name;
    private $description;
    private $assignableId;
    private $assignableType;
    private $modelId;
    private $modelType;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?InductionId $id,
        InductionName $name,
        InductionDescription $description,
        InductionAssignableId $assignableId,
        InductionAssignableType $assignableType,
        InductionModelId $modelId,
        InductionModelType $modelType,
        ?InductionCreatedAt $createdAt = null,
        ?InductionUpdatedAt $updatedAt = null,
        ?InductionDeletedAt $deletedAt = null,
        ?InductionRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->assignableId = $assignableId;
        $this->assignableType = $assignableType;
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?InductionId
    {
        return $this->id;
    }

    public function name(): InductionName
    {
        return $this->name;
    }

    public function description(): InductionDescription
    {
        return $this->description;
    }

    public function assignableId(): InductionAssignableId
    {
        return $this->assignableId;
    }

    public function assignableType(): InductionAssignableType
    {
        return $this->assignableType;
    }

    public function modelId(): ?InductionModelId
    {
        return $this->modelId;
    }

    public function modelType(): InductionModelType
    {
        return $this->modelType;
    }

    public function createdAt(): ?InductionCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?InductionUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?InductionDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?InductionRelationships
    {
        return $this->relationships;
    }
}
