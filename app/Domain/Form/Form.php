<?php

declare(strict_types=1);

namespace App\Domain\Form;

use App\Domain\Form\Values\FormAssignableId;
use App\Domain\Form\Values\FormAssignableType;
use App\Domain\Form\Values\FormCreatedAt;
use App\Domain\Form\Values\FormDeletedAt;
use App\Domain\Form\Values\FormPositionId;
use App\Domain\Form\Values\FormId;
use App\Domain\Form\Values\FormFormTypeId;
use App\Domain\Form\Values\FormEntityId;
use App\Domain\Form\Values\FormModelId;
use App\Domain\Form\Values\FormModelType;
use App\Domain\Form\Values\FormQuestions;
use App\Domain\Form\Values\FormRelationships;
use App\Domain\Form\Values\FormUpdatedAt;

final class Form
{
    private $id;
    private $formTypeId;
    // private $entityId;
    // private $positionId;
    private $assignableId;
    private $assignableType;
    private $modelId;
    private $modelType;
    private $questions;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?FormId $id,
        FormFormTypeId $formTypeId,
        // FormEntityId $entityId,
        // FormPositionId $positionId,
        FormAssignableId $assignableId,
        FormAssignableType $assignableType,
        FormModelId $modelId,
        FormModelType $modelType,
        FormQuestions $questions,
        ?FormCreatedAt $createdAt = null,
        ?FormUpdatedAt $updatedAt = null,
        ?FormDeletedAt $deletedAt = null,
        ?FormRelationships $relationships = null
    ) {
        $this->id = $id;
        $this->formTypeId = $formTypeId;
        // $this->entityId = $entityId;
        // $this->positionId = $positionId;
        $this->assignableId = $assignableId;
        $this->assignableType = $assignableType;
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->questions = $questions;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?FormId
    {
        return $this->id;
    }

    public function formTypeId(): FormFormTypeId
    {
        return $this->formTypeId;
    }

    public function assignableId(): FormAssignableId
    {
        return $this->assignableId;
    }

    public function assignableType(): FormAssignableType
    {
        return $this->assignableType;
    }

    public function modelId(): ?FormModelId
    {
        return $this->modelId;
    }

    public function modelType(): FormModelType
    {
        return $this->modelType;
    }

    public function questions(): FormQuestions
    {
        return $this->questions;
    }

    public function createdAt(): ?FormCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FormUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?FormDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?FormRelationships
    {
        return $this->relationships;
    }
}
