<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph;

use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphServiceProviderId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphApplicationId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphCreatedAt;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphDateTime;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphIndication;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphCityId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphRelationships;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphResult;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphAddress;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphNeighborhood;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphDescription;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphStatus;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphUpdatedAt;

final class ExamPolygraph
{
    private $id;
    private $applicationId;
    private $dateTime;
    private $cityId;
    private $serviceProviderId;
    private $address;
    private $neighborhood;
    private $result;
    private $description;
    private $indication;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?ExamPolygraphId $id,
        ExamPolygraphApplicationId $applicationId,
        ExamPolygraphDateTime $dateTime,
        ExamPolygraphCityId $cityId,
        ExamPolygraphServiceProviderId $serviceProviderId,
        ExamPolygraphAddress $address,
        ExamPolygraphNeighborhood $neighborhood,
        ExamPolygraphResult $result,
        ExamPolygraphDescription $description,
        ExamPolygraphIndication $indication,
        ExamPolygraphStatus $status,
        ?ExamPolygraphCreatedAt $createdAt = null,
        ?ExamPolygraphUpdatedAt $updatedAt = null,
        ?ExamPolygraphRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->dateTime = $dateTime;
        $this->cityId = $cityId;
        $this->serviceProviderId = $serviceProviderId;
        $this->address = $address;
        $this->neighborhood = $neighborhood;
        $this->result = $result;
        $this->description = $description;
        $this->indication = $indication;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ExamPolygraphId
    {
        return $this->id;
    }

    public function applicationId(): ExamPolygraphApplicationId
    {
        return $this->applicationId;
    }

    public function dateTime(): ExamPolygraphDateTime
    {
        return $this->dateTime;
    }

    public function cityId(): ExamPolygraphCityId
    {
        return $this->cityId;
    }

    public function serviceProviderId(): ExamPolygraphServiceProviderId
    {
        return $this->serviceProviderId;
    }

    public function address(): ExamPolygraphAddress
    {
        return $this->address;
    }

    public function neighborhood(): ExamPolygraphNeighborhood
    {
        return $this->neighborhood;
    }

    public function result(): ExamPolygraphResult
    {
        return $this->result;
    }

    public function description(): ExamPolygraphDescription
    {
        return $this->description;
    }

    public function indication(): ExamPolygraphIndication
    {
        return $this->indication;
    }

    public function status(): ExamPolygraphStatus
    {
        return $this->status;
    }

    public function createdAt(): ?ExamPolygraphCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ExamPolygraphUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?ExamPolygraphRelationships
    {
        return $this->relationships;
    }
}
