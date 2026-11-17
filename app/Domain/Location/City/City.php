<?php

declare(strict_types=1);

namespace App\Domain\Location\City;

use App\Domain\Location\City\Values\CityCreatedAt;
use App\Domain\Location\City\Values\CityDepartamentId;
use App\Domain\Location\City\Values\CityId;
use App\Domain\Location\City\Values\CityLatitude;
use App\Domain\Location\City\Values\CityLongitude;
use App\Domain\Location\City\Values\CityName;
use App\Domain\Location\City\Values\CityUpdatedAt;

final class City
{
    private $id;
    private $departamentId;
    private $name;
    private $latitude;
    private $longitude;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        CityId $id,
        CityDepartamentId $departamentId,
        CityName $name,
        CityLatitude $latitude,
        CityLongitude $longitude,
        CityCreatedAt $createdAt,
        CityUpdatedAt $updatedAt
    )
    {
        $this->id = $id;
        $this->departamentId = $departamentId;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?CityId
    {
        return $this->id;
    }

    public function departamentId(): CityDepartamentId
    {
        return $this->departamentId;
    }
    
    public function name(): CityName
    {
        return $this->name;
    }

    public function latitude(): CityLatitude
    {
        return $this->latitude;
    }

    public function longitude(): CityLongitude
    {
        return $this->longitude;
    }

    public function createdAt(): CityCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): CityUpdatedAt
    {
        return $this->updatedAt;
    }
}
