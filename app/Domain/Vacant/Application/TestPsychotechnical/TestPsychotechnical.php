<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical;

use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalAnswers;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalApplicationId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalCreatedAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalEndAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalEndDate;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalFormId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStartDate;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalIndication;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistObservation;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistStatus;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalQuestions;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalRelationships;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStartAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStatus;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalUpdatedAt;

final class TestPsychotechnical
{
    private $id;
    private $applicationId;
    private $startDate;
    private $endDate;
    private $formId;
    private $questions;
    private $answers;
    private $psychologistObservation;
    private $psychologistId;
    private $psychologistStatus;
    private $startAt;
    private $endAt;
    private $indication;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?TestPsychotechnicalId $id,
        TestPsychotechnicalApplicationId $applicationId,
        TestPsychotechnicalStartDate $startDate,
        TestPsychotechnicalEndDate $endDate,
        TestPsychotechnicalFormId $formId,
        TestPsychotechnicalQuestions $questions,
        TestPsychotechnicalAnswers $answers,
        ?TestPsychotechnicalPsychologistObservation $psychologistObservation = null,
        ?TestPsychotechnicalPsychologistId $psychologistId = null,
        TestPsychotechnicalPsychologistStatus $psychologistStatus,
        TestPsychotechnicalStartAt $startAt,
        TestPsychotechnicalEndAt $endAt,
        TestPsychotechnicalIndication $indication,
        TestPsychotechnicalStatus $status,
        ?TestPsychotechnicalCreatedAt $createdAt = null,
        ?TestPsychotechnicalUpdatedAt $updatedAt = null,
        ?TestPsychotechnicalRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->formId = $formId;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->psychologistObservation = $psychologistObservation;
        $this->psychologistId = $psychologistId;
        $this->psychologistStatus = $psychologistStatus;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->indication = $indication;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?TestPsychotechnicalId
    {
        return $this->id;
    }

    public function applicationId(): TestPsychotechnicalApplicationId
    {
        return $this->applicationId;
    }

    public function startDate(): TestPsychotechnicalStartDate
    {
        return $this->startDate;
    }

    public function endDate(): TestPsychotechnicalEndDate
    {
        return $this->endDate;
    }

    public function formId(): TestPsychotechnicalFormId
    {
        return $this->formId;
    }

    public function questions(): TestPsychotechnicalQuestions
    {
        return $this->questions;
    }

    public function answers(): TestPsychotechnicalAnswers
    {
        return $this->answers;
    }

    public function psychologistObservation(): TestPsychotechnicalPsychologistObservation
    {
        return $this->psychologistObservation;
    }

    public function psychologistId(): TestPsychotechnicalPsychologistId
    {
        return $this->psychologistId;
    }

    public function psychologistStatus(): TestPsychotechnicalPsychologistStatus
    {
        return $this->psychologistStatus;
    }

    public function startAt(): TestPsychotechnicalStartAt
    {
        return $this->startAt;
    }

    public function endAt(): TestPsychotechnicalEndAt
    {
        return $this->endAt;
    }

    public function indication(): TestPsychotechnicalIndication
    {
        return $this->indication;
    }

    public function status(): TestPsychotechnicalStatus
    {
        return $this->status;
    }

    public function createdAt(): ?TestPsychotechnicalCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?TestPsychotechnicalUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?TestPsychotechnicalRelationships
    {
        return $this->relationships;
    }
}
