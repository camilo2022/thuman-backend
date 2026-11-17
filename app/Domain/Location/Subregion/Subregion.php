<?php

declare(strict_types=1);

namespace App\Domain\Location\Subregion;

use App\Domain\Location\Subregion\Values\SubregionCreatedAt;
use App\Domain\Location\Subregion\Values\SubregionId;
use App\Domain\Location\Subregion\Values\SubregionName;
use App\Domain\Location\Subregion\Values\SubregionRegionId;
use App\Domain\Location\Subregion\Values\SubregionTranslations;
use App\Domain\Location\Subregion\Values\SubregionUpdatedAt;

final class Subregion
{
    private $id;
    private $regionId;
    private $name;
    private $translations;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        SubregionId $id,
        SubregionRegionId $regionId,
        SubregionName $name,
        SubregionTranslations $translations,
        SubregionCreatedAt $createdAt,
        SubregionUpdatedAt $updatedAt
    )
    {
        $this->id = $id;
        $this->regionId = $regionId;
        $this->name = $name;
        $this->translations = $translations;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?SubregionId
    {
        return $this->id;
    }

    public function regionId(): SubregionRegionId
    {
        return $this->regionId;
    }

    public function name(): SubregionName
    {
        return $this->name;
    }

    public function translations(): SubregionTranslations
    {
        return $this->translations;
    }

    public function createdAt(): SubregionCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): SubregionUpdatedAt
    {
        return $this->updatedAt;
    }
}
