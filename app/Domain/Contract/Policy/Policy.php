<?php

declare(strict_types=1);

namespace App\Domain\Contract\Policy;

use App\Domain\Contract\Policy\Values\PolicyId;
use App\Domain\Contract\Policy\Values\PolicyCode;
use App\Domain\Contract\Policy\Values\PolicyCreatedAt;
use App\Domain\Contract\Policy\Values\PolicyEndDate;
use App\Domain\Contract\Policy\Values\PolicyInsuredValue;
use App\Domain\Contract\Policy\Values\PolicyModelId;
use App\Domain\Contract\Policy\Values\PolicyModelType;
use App\Domain\Contract\Policy\Values\PolicyPolicyTypeId;
use App\Domain\Contract\Policy\Values\PolicyRelationships;
use App\Domain\Contract\Policy\Values\PolicyRiskManagerId;
use App\Domain\Contract\Policy\Values\PolicyStartDate;
use App\Domain\Contract\Policy\Values\PolicyStatus;
use App\Domain\Contract\Policy\Values\PolicyUpdatedAt;
use App\Domain\Contract\Policy\Values\PolicyUuid;
use App\Domain\Contract\Policy\Values\PolicyValue;

final class Policy
{
    private $id;
    private $uuid;
    private $modelId;
    private $modelType;
    private $riskManagerId;
    private $policyTypeId;
    private $code;
    private $value;
    private $insuredValue;
    private $startDate;
    private $endDate;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?PolicyId $id,
        PolicyUuid $uuid,
        PolicyModelId $modelId,
        PolicyModelType $modelType,
        PolicyRiskManagerId $riskManagerId,
        PolicyPolicyTypeId $policyTypeId,
        PolicyCode $code,
        PolicyValue $value,
        PolicyInsuredValue $insuredValue,
        PolicyStartDate $startDate,
        PolicyEndDate $endDate,
        PolicyStatus $status,
        ?PolicyCreatedAt $createdAt = null,
        ?PolicyUpdatedAt $updatedAt = null,
        ?PolicyRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->riskManagerId = $riskManagerId;
        $this->policyTypeId = $policyTypeId;
        $this->code = $code;
        $this->value = $value;
        $this->insuredValue = $insuredValue;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?PolicyId
    {
        return $this->id;
    }

    public function uuid(): PolicyUuid
    {
        return $this->uuid;
    }

    public function modelId(): PolicyModelId
    {
        return $this->modelId;
    }

    public function modelType(): PolicyModelType
    {
        return $this->modelType;
    }

    public function riskManagerId(): PolicyRiskManagerId
    {
        return $this->riskManagerId;
    }

    public function policyTypeId(): PolicyPolicyTypeId
    {
        return $this->policyTypeId;
    }

    public function code(): PolicyCode
    {
        return $this->code;
    }

    public function value(): PolicyValue
    {
        return $this->value;
    }

    public function insuredValue(): PolicyInsuredValue
    {
        return $this->insuredValue;
    }

    public function startDate(): PolicyStartDate
    {
        return $this->startDate;
    }

    public function endDate(): PolicyEndDate
    {
        return $this->endDate;
    }

    public function status(): PolicyStatus
    {
        return $this->status;
    }

    public function createdAt(): ?PolicyCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PolicyUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?PolicyRelationships
    {
        return $this->relationships;
    }
}
