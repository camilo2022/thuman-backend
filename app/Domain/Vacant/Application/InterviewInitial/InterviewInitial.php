<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial;

use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialAnswers;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialApplicationId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialCreatedAt;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialDateTime;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialFormId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialIndication;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderObservation;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderScore;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialLeaderStatus;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialQuestions;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialRelationships;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerId;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerObservation;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerScore;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialReviewerStatus;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialSalaryMax;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialSalaryMin;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialStatus;
use App\Domain\Vacant\Application\InterviewInitial\Values\InterviewInitialUpdatedAt;

final class InterviewInitial
{
    private $id;
    private $applicationId;
    private $dateTime;
    private $formId;
    private $questions;
    private $answers;
    private $salaryMin;
    private $salaryMax;
    private $reviewerObservation;
    private $reviewerScore;
    private $reviewerId;
    private $reviewerStatus;
    private $leaderObservation;
    private $leaderScore;
    private $leaderId;
    private $leaderStatus;
    private $indication;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?InterviewInitialId $id,
        InterviewInitialApplicationId $applicationId,
        InterviewInitialDateTime $dateTime,
        InterviewInitialFormId $formId,
        InterviewInitialQuestions $questions,
        InterviewInitialAnswers $answers,
        InterviewInitialSalaryMin $salaryMin,
        ?InterviewInitialSalaryMax $salaryMax = null,
        ?InterviewInitialReviewerObservation $reviewerObservation = null,
        ?InterviewInitialReviewerScore $reviewerScore = null,
        ?InterviewInitialReviewerId $reviewerId = null,
        InterviewInitialReviewerStatus $reviewerStatus,
        ?InterviewInitialLeaderObservation $leaderObservation = null,
        ?InterviewInitialLeaderScore $leaderScore = null,
        ?InterviewInitialLeaderId $leaderId = null,
        InterviewInitialLeaderStatus $leaderStatus,
        InterviewInitialIndication $indication,
        InterviewInitialStatus $status,
        ?InterviewInitialCreatedAt $createdAt = null,
        ?InterviewInitialUpdatedAt $updatedAt = null,
        ?InterviewInitialRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->dateTime = $dateTime;
        $this->formId = $formId;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->salaryMin = $salaryMin;
        $this->salaryMax = $salaryMax;
        $this->reviewerObservation = $reviewerObservation;
        $this->reviewerScore = $reviewerScore;
        $this->reviewerId = $reviewerId;
        $this->reviewerStatus = $reviewerStatus;
        $this->leaderObservation = $leaderObservation;
        $this->leaderScore = $leaderScore;
        $this->leaderId = $leaderId;
        $this->leaderStatus = $leaderStatus;
        $this->indication = $indication;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?InterviewInitialId
    {
        return $this->id;
    }

    public function applicationId(): InterviewInitialApplicationId
    {
        return $this->applicationId;
    }

    public function dateTime(): InterviewInitialDateTime
    {
        return $this->dateTime;
    }

    public function formId(): InterviewInitialFormId
    {
        return $this->formId;
    }

    public function questions(): InterviewInitialQuestions
    {
        return $this->questions;
    }

    public function answers(): InterviewInitialAnswers
    {
        return $this->answers;
    }

    public function salaryMin(): InterviewInitialSalaryMin
    {
        return $this->salaryMin;
    }

    public function salaryMax(): ?InterviewInitialSalaryMax
    {
        return $this->salaryMax;
    }

    public function reviewerObservation(): InterviewInitialReviewerObservation
    {
        return $this->reviewerObservation;
    }

    public function reviewerScore(): InterviewInitialReviewerScore
    {
        return $this->reviewerScore;
    }

    public function reviewerId(): InterviewInitialReviewerId
    {
        return $this->reviewerId;
    }

    public function reviewerStatus(): InterviewInitialReviewerStatus
    {
        return $this->reviewerStatus;
    }

    public function leaderObservation(): InterviewInitialLeaderObservation
    {
        return $this->leaderObservation;
    }

    public function leaderScore(): InterviewInitialLeaderScore
    {
        return $this->leaderScore;
    }

    public function leaderId(): InterviewInitialLeaderId
    {
        return $this->leaderId;
    }

    public function leaderStatus(): InterviewInitialLeaderStatus
    {
        return $this->leaderStatus;
    }

    public function indication(): InterviewInitialIndication
    {
        return $this->indication;
    }

    public function status(): InterviewInitialStatus
    {
        return $this->status;
    }

    public function createdAt(): ?InterviewInitialCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?InterviewInitialUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?InterviewInitialRelationships
    {
        return $this->relationships;
    }
}
