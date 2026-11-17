<?php

declare(strict_types=1);

namespace App\Domain\Location\Departament;

use App\Domain\Location\Departament\Values\DepartamentCountryId;
use App\Domain\Location\Departament\Values\DepartamentCreatedAt;
use App\Domain\Location\Departament\Values\DepartamentId;
use App\Domain\Location\Departament\Values\DepartamentLatitude;
use App\Domain\Location\Departament\Values\DepartamentLongitude;
use App\Domain\Location\Departament\Values\DepartamentName;
use App\Domain\Location\Departament\Values\DepartamentStatusCode;
use App\Domain\Location\Departament\Values\DepartamentType;
use App\Domain\Location\Departament\Values\DepartamentUpdatedAt;

final class Departament
{
    private $id;
    private $countryId;
    private $name;
    private $stateCode;
    private $type;
    private $latitude;
    private $longitude;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        DepartamentId $id,
        DepartamentCountryId $countryId,
        DepartamentName $name,
        DepartamentStatusCode $stateCode,
        DepartamentType $type,
        DepartamentLatitude $latitude,
        DepartamentLongitude $longitude,
        DepartamentCreatedAt $createdAt,
        DepartamentUpdatedAt $updatedAt
    )
    {
        $this->id = $id;
        $this->countryId = $countryId;
        $this->name = $name;
        $this->stateCode = $stateCode;
        $this->type = $type;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): ?DepartamentId
    {
        return $this->id;
    }

    public function countryId(): DepartamentCountryId
    {
        return $this->countryId;
    }
    
    public function name(): DepartamentName
    {
        return $this->name;
    }

    public function stateCode(): DepartamentStatusCode
    {
        return $this->stateCode;
    }
    
    public function type(): DepartamentType
    {
        return $this->type;
    }

    public function latitude(): DepartamentLatitude
    {
        return $this->latitude;
    }

    public function longitude(): DepartamentLongitude
    {
        return $this->longitude;
    }

    public function createdAt(): DepartamentCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): DepartamentUpdatedAt
    {
        return $this->updatedAt;
    }
}
