<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewVideo;

use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoAnswers;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoApplicationId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoCreatedAt;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoFormId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoQuestions;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoRelationships;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoReviewerId;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoReviewerObservation;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoStatus;
use App\Domain\Vacant\Application\InterviewVideo\Values\InterviewVideoUpdatedAt;

final class InterviewVideo
{
    private $id;
    private $applicationId;
    private $formId;
    private $questions;
    private $answers;
    private $reviewerObservation;
    private $reviewerId;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?InterviewVideoId $id,
        InterviewVideoApplicationId $applicationId,
        InterviewVideoFormId $formId,
        InterviewVideoQuestions $questions,
        InterviewVideoAnswers $answers,
        InterviewVideoReviewerObservation $reviewerObservation,
        InterviewVideoReviewerId $reviewerId,
        InterviewVideoStatus $status,
        ?InterviewVideoCreatedAt $createdAt = null,
        ?InterviewVideoUpdatedAt $updatedAt = null,
        ?InterviewVideoRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->applicationId = $applicationId;
        $this->formId = $formId;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->reviewerObservation = $reviewerObservation;
        $this->reviewerId = $reviewerId;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?InterviewVideoId
    {
        return $this->id;
    }

    public function applicationId(): InterviewVideoApplicationId
    {
        return $this->applicationId;
    }

    public function formId(): InterviewVideoFormId
    {
        return $this->formId;
    }

    public function questions(): InterviewVideoQuestions
    {
        return $this->questions;
    }

    public function answers(): InterviewVideoAnswers
    {
        return $this->answers;
    }

    public function reviewerObservation(): InterviewVideoReviewerObservation
    {
        return $this->reviewerObservation;
    }

    public function reviewerId(): InterviewVideoReviewerId
    {
        return $this->reviewerId;
    }

    public function status(): InterviewVideoStatus
    {
        return $this->status;
    }

    public function createdAt(): ?InterviewVideoCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?InterviewVideoUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?InterviewVideoRelationships
    {
        return $this->relationships;
    }
}
