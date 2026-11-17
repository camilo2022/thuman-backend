<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical;

use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalAnswers;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalApplicationId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalCreatedAt;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalDateTime;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalIndication;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalQuestions;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalRelationships;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistId;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistObservation;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistScore;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalPsychologistStatus;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalStatus;
use App\Domain\Vacant\Application\InterviewPsychotechnical\Values\InterviewPsychotechnicalUpdatedAt;

final class InterviewPsychotechnical
{
    private $id;
    private $applicationId;
    private $dateTime;
    private $questions;
    private $answers;
    private $psychologistObservation;
    private $psychologistScore;
    private $psychologistId;
    private $psychologistStatus;
    private $indication;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?InterviewPsychotechnicalId $id,
        InterviewPsychotechnicalApplicationId $applicationId,
        InterviewPsychotechnicalDateTime $dateTime,
        InterviewPsychotechnicalQuestions $questions,
        InterviewPsychotechnicalAnswers $answers,
        ?InterviewPsychotechnicalPsychologistObservation $psychologistObservation = null,
        ?InterviewPsychotechnicalPsychologistScore $psychologistScore = null,
        ?InterviewPsychotechnicalPsychologistId $psychologistId = null,
        InterviewPsychotechnicalPsychologistStatus $psychologistStatus,
        InterviewPsychotechnicalIndication $indication,
        InterviewPsychotechnicalStatus $status,
        ?InterviewPsychotechnicalCreatedAt $createdAt = null,
        ?InterviewPsychotechnicalUpdatedAt $updatedAt = null,
        ?InterviewPsychotechnicalRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->dateTime = $dateTime;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->psychologistObservation = $psychologistObservation;
        $this->psychologistScore = $psychologistScore;
        $this->psychologistId = $psychologistId;
        $this->psychologistStatus = $psychologistStatus;
        $this->indication = $indication;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?InterviewPsychotechnicalId
    {
        return $this->id;
    }

    public function applicationId(): InterviewPsychotechnicalApplicationId
    {
        return $this->applicationId;
    }

    public function dateTime(): InterviewPsychotechnicalDateTime
    {
        return $this->dateTime;
    }

    public function questions(): InterviewPsychotechnicalQuestions
    {
        return $this->questions;
    }

    public function answers(): InterviewPsychotechnicalAnswers
    {
        return $this->answers;
    }

    public function psychologistObservation(): InterviewPsychotechnicalPsychologistObservation
    {
        return $this->psychologistObservation;
    }

    public function psychologistScore(): InterviewPsychotechnicalPsychologistScore
    {
        return $this->psychologistScore;
    }

    public function psychologistId(): InterviewPsychotechnicalPsychologistId
    {
        return $this->psychologistId;
    }

    public function psychologistStatus(): InterviewPsychotechnicalPsychologistStatus
    {
        return $this->psychologistStatus;
    }

    public function indication(): InterviewPsychotechnicalIndication
    {
        return $this->indication;
    }

    public function status(): InterviewPsychotechnicalStatus
    {
        return $this->status;
    }

    public function createdAt(): ?InterviewPsychotechnicalCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?InterviewPsychotechnicalUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?InterviewPsychotechnicalRelationships
    {
        return $this->relationships;
    }
}
