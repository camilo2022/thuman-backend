<?php

declare(strict_types=1);

namespace App\Domain\Induction\Step;

use App\Domain\Induction\Step\Values\StepCreatedAt;
use App\Domain\Induction\Step\Values\StepDeletedAt;
use App\Domain\Induction\Step\Values\StepDescription;
use App\Domain\Induction\Step\Values\StepFormId;
use App\Domain\Induction\Step\Values\StepId;
use App\Domain\Induction\Step\Values\StepInductionId;
use App\Domain\Induction\Step\Values\StepName;
use App\Domain\Induction\Step\Values\StepRelationships;
use App\Domain\Induction\Step\Values\StepUpdatedAt;

final class Step
{
    private $id;
    private $inductionId;
    private $name;
    private $description;
    private $formId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?StepId $id,
        StepInductionId $inductionId,
        StepName $name,
        StepDescription $description,
        StepFormId $formId,
        ?StepCreatedAt $createdAt = null,
        ?StepUpdatedAt $updatedAt = null,
        ?StepDeletedAt $deletedAt = null,
        ?StepRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->inductionId = $inductionId;
        $this->name = $name;
        $this->description = $description;
        $this->formId = $formId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?StepId
    {
        return $this->id;
    }

    public function inductionId(): StepInductionId
    {
        return $this->inductionId;
    }

    public function name(): StepName
    {
        return $this->name;
    }

    public function description(): StepDescription
    {
        return $this->description;
    }

    public function formId(): ?StepFormId
    {
        return $this->formId;
    }

    public function createdAt(): ?StepCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?StepUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?StepDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?StepRelationships
    {
        return $this->relationships;
    }
}
