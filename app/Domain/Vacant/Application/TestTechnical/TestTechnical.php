<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical;

use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalAnswers;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalApplicationId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalCreatedAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalEndAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalEndDate;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalFormId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStartDate;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalIndication;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderObservation;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderStatus;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalQuestions;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalRelationships;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStartAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStatus;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalUpdatedAt;

final class TestTechnical
{
    private $id;
    private $applicationId;
    private $startDate;
    private $endDate;
    private $formId;
    private $questions;
    private $answers;
    private $leaderObservation;
    private $leaderId;
    private $leaderStatus;
    private $startAt;
    private $endAt;
    private $indication;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?TestTechnicalId $id,
        TestTechnicalApplicationId $applicationId,
        TestTechnicalStartDate $startDate,
        TestTechnicalEndDate $endDate,
        TestTechnicalFormId $formId,
        TestTechnicalQuestions $questions,
        TestTechnicalAnswers $answers,
        ?TestTechnicalLeaderObservation $leaderObservation = null,
        ?TestTechnicalLeaderId $leaderId = null,
        TestTechnicalLeaderStatus $leaderStatus,
        TestTechnicalStartAt $startAt,
        TestTechnicalEndAt $endAt,
        TestTechnicalIndication $indication,
        TestTechnicalStatus $status,
        ?TestTechnicalCreatedAt $createdAt = null,
        ?TestTechnicalUpdatedAt $updatedAt = null,
        ?TestTechnicalRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->formId = $formId;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->leaderObservation = $leaderObservation;
        $this->leaderId = $leaderId;
        $this->leaderStatus = $leaderStatus;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->indication = $indication;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?TestTechnicalId
    {
        return $this->id;
    }

    public function applicationId(): TestTechnicalApplicationId
    {
        return $this->applicationId;
    }

    public function startDate(): TestTechnicalStartDate
    {
        return $this->startDate;
    }

    public function endDate(): TestTechnicalEndDate
    {
        return $this->endDate;
    }

    public function formId(): TestTechnicalFormId
    {
        return $this->formId;
    }

    public function questions(): TestTechnicalQuestions
    {
        return $this->questions;
    }

    public function answers(): TestTechnicalAnswers
    {
        return $this->answers;
    }

    public function leaderObservation(): TestTechnicalLeaderObservation
    {
        return $this->leaderObservation;
    }

    public function leaderId(): TestTechnicalLeaderId
    {
        return $this->leaderId;
    }

    public function leaderStatus(): TestTechnicalLeaderStatus
    {
        return $this->leaderStatus;
    }

    public function startAt(): TestTechnicalStartAt
    {
        return $this->startAt;
    }

    public function endAt(): TestTechnicalEndAt
    {
        return $this->endAt;
    }

    public function indication(): TestTechnicalIndication
    {
        return $this->indication;
    }

    public function status(): TestTechnicalStatus
    {
        return $this->status;
    }

    public function createdAt(): ?TestTechnicalCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?TestTechnicalUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?TestTechnicalRelationships
    {
        return $this->relationships;
    }
}
