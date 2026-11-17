<?php

declare(strict_types=1);

namespace App\Domain\Benefit;

use App\Domain\Benefit\Values\BenefitCreatedAt;
use App\Domain\Benefit\Values\BenefitDeletedAt;
use App\Domain\Benefit\Values\BenefitDescription;
use App\Domain\Benefit\Values\BenefitId;
use App\Domain\Benefit\Values\BenefitItemId;
use App\Domain\Benefit\Values\BenefitName;
use App\Domain\Benefit\Values\BenefitSettings;
use App\Domain\Benefit\Values\BenefitUpdatedAt;

final class Benefit
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $icon;

    public function __construct(
        ?BenefitId $id,
        BenefitItemId $itemId,
        BenefitName $name,
        BenefitDescription $description,
        BenefitSettings $settings,
        ?BenefitCreatedAt $createdAt = null,
        ?BenefitUpdatedAt $updatedAt = null,
        ?BenefitDeletedAt $deletedAt = null,
        ?Icon $icon = null
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
        $this->icon = $icon;
    }

    public function id(): ?BenefitId
    {
        return $this->id;
    }

    public function itemId(): BenefitItemId
    {
        return $this->itemId;
    }

    public function name(): BenefitName
    {
        return $this->name;
    }

    public function description(): BenefitDescription
    {
        return $this->description;
    }

    public function settings(): BenefitSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?BenefitCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?BenefitUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?BenefitDeletedAt
    {
        return $this->deletedAt;
    }

    public function icon(): ?Icon
    {
        return $this->icon;
    }
}
