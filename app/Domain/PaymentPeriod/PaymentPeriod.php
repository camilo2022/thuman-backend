<?php

declare(strict_types=1);

namespace App\Domain\PaymentPeriod;

use App\Domain\PaymentPeriod\Values\PaymentPeriodCreatedAt;
use App\Domain\PaymentPeriod\Values\PaymentPeriodDeletedAt;
use App\Domain\PaymentPeriod\Values\PaymentPeriodDescription;
use App\Domain\PaymentPeriod\Values\PaymentPeriodId;
use App\Domain\PaymentPeriod\Values\PaymentPeriodItemId;
use App\Domain\PaymentPeriod\Values\PaymentPeriodName;
use App\Domain\PaymentPeriod\Values\PaymentPeriodSettings;
use App\Domain\PaymentPeriod\Values\PaymentPeriodUpdatedAt;

final class PaymentPeriod
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
        ?PaymentPeriodId $id,
        PaymentPeriodItemId $itemId,
        PaymentPeriodName $name,
        PaymentPeriodDescription $description,
        PaymentPeriodSettings $settings,
        ?PaymentPeriodCreatedAt $createdAt = null,
        ?PaymentPeriodUpdatedAt $updatedAt = null,
        ?PaymentPeriodDeletedAt $deletedAt = null
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

    public function id(): ?PaymentPeriodId
    {
        return $this->id;
    }

    public function itemId(): PaymentPeriodItemId
    {
        return $this->itemId;
    }

    public function name(): PaymentPeriodName
    {
        return $this->name;
    }

    public function description(): PaymentPeriodDescription
    {
        return $this->description;
    }

    public function settings(): PaymentPeriodSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?PaymentPeriodCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PaymentPeriodUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?PaymentPeriodDeletedAt
    {
        return $this->deletedAt;
    }
}
