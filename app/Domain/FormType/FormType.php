<?php

declare(strict_types=1);

namespace App\Domain\FormType;

use App\Domain\FormType\Values\FormTypeCreatedAt;
use App\Domain\FormType\Values\FormTypeDeletedAt;
use App\Domain\FormType\Values\FormTypeDescription;
use App\Domain\FormType\Values\FormTypeId;
use App\Domain\FormType\Values\FormTypeItemId;
use App\Domain\FormType\Values\FormTypeName;
use App\Domain\FormType\Values\FormTypeSettings;
use App\Domain\FormType\Values\FormTypeUpdatedAt;

final class FormType
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?FormTypeId $id,
        FormTypeItemId $itemId,
        FormTypeName $name,
        FormTypeDescription $description,
        FormTypeSettings $settings,
        ?FormTypeCreatedAt $createdAt = null,
        ?FormTypeUpdatedAt $updatedAt = null,
        ?FormTypeDeletedAt $deletedAt = null
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
    }

    public function id(): ?FormTypeId
    {
        return $this->id;
    }

    public function itemId(): FormTypeItemId
    {
        return $this->itemId;
    }

    public function name(): FormTypeName
    {
        return $this->name;
    }

    public function description(): FormTypeDescription
    {
        return $this->description;
    }

    public function settings(): FormTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?FormTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FormTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?FormTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
