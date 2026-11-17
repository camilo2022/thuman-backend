<?php

declare(strict_types=1);

namespace App\Domain\Location\Region;

use App\Domain\Location\Region\Values\RegionCreatedAt;
use App\Domain\Location\Region\Values\RegionId;
use App\Domain\Location\Region\Values\RegionName;
use App\Domain\Location\Region\Values\RegionTranslations;
use App\Domain\Location\Region\Values\RegionUpdatedAt;

final class Region
{
    private $id;
    private $name;
    private $translations;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        RegionId $id,
        RegionName $name,
        RegionTranslations $translations,
        RegionCreatedAt $createdAt,
        RegionUpdatedAt $updatedAt
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->translations = $translations;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): RegionId
    {
        return $this->id;
    }

    public function name(): RegionName
    {
        return $this->name;
    }

    public function translations(): RegionTranslations
    {
        return $this->translations;
    }

    public function createdAt(): RegionCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): RegionUpdatedAt
    {
        return $this->updatedAt;
    }
}
