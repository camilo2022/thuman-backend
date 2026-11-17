<?php

declare(strict_types=1);

namespace App\Domain\Size;

use App\Domain\Size\Values\SizeCreatedAt;
use App\Domain\Size\Values\SizeDeletedAt;
use App\Domain\Size\Values\SizeDescription;
use App\Domain\Size\Values\SizeId;
use App\Domain\Size\Values\SizeItemId;
use App\Domain\Size\Values\SizeName;
use App\Domain\Size\Values\SizeSettings;
use App\Domain\Size\Values\SizeUpdatedAt;

final class Size
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
        ?SizeId $id,
        SizeItemId $itemId,
        SizeName $name,
        SizeDescription $description,
        SizeSettings $settings,
        ?SizeCreatedAt $createdAt = null,
        ?SizeUpdatedAt $updatedAt = null,
        ?SizeDeletedAt $deletedAt = null
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

    public function id(): ?SizeId
    {
        return $this->id;
    }

    public function itemId(): SizeItemId
    {
        return $this->itemId;
    }

    public function name(): SizeName
    {
        return $this->name;
    }

    public function description(): SizeDescription
    {
        return $this->description;
    }

    public function settings(): SizeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?SizeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SizeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?SizeDeletedAt
    {
        return $this->deletedAt;
    }
}
