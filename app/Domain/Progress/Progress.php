<?php

declare(strict_types=1);

namespace App\Domain\Progress;

use App\Domain\Progress\Values\ProgressAnswers;
use App\Domain\Progress\Values\ProgressCreatedAt;
use App\Domain\Progress\Values\ProgressDeletedAt;
use App\Domain\Progress\Values\ProgressEndAt;
use App\Domain\Progress\Values\ProgressId;
use App\Domain\Progress\Values\ProgressPersonId;
use App\Domain\Progress\Values\ProgressQuestions;
use App\Domain\Progress\Values\ProgressRelationships;
use App\Domain\Progress\Values\ProgressResult;
use App\Domain\Progress\Values\ProgressStartAt;
use App\Domain\Progress\Values\ProgressStatus;
use App\Domain\Progress\Values\ProgressStepId;
use App\Domain\Progress\Values\ProgressUpdatedAt;

final class Progress
{
    private $id;
    private $personId;
    private $stepId;
    private $questions;
    private $answers;
    private $startAt;
    private $endAt;
    private $status;
    private $result;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?ProgressId $id,
        ProgressPersonId $personId,
        ProgressStepId $stepId,
        ProgressQuestions $questions,
        ProgressAnswers $answers,
        ProgressStartAt $startAt,
        ProgressEndAt $endAt,
        ProgressStatus $status,
        ProgressResult $result,
        ?ProgressCreatedAt $createdAt = null,
        ?ProgressUpdatedAt $updatedAt = null,
        ?ProgressDeletedAt $deletedAt = null,
        ?ProgressRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->stepId = $stepId;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->status = $status;
        $this->result = $result;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ProgressId
    {
        return $this->id;
    }

    public function personId(): ProgressPersonId
    {
        return $this->personId;
    }

    public function stepId(): ProgressStepId
    {
        return $this->stepId;
    }

    public function questions(): ProgressQuestions
    {
        return $this->questions;
    }

    public function answers(): ProgressAnswers
    {
        return $this->answers;
    }

    public function startAt(): ProgressStartAt
    {
        return $this->startAt;
    }

    public function endAt(): ProgressEndAt
    {
        return $this->endAt;
    }

    public function status(): ProgressStatus
    {
        return $this->status;
    }

    public function result(): ProgressResult
    {
        return $this->result;
    }

    public function createdAt(): ?ProgressCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ProgressUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ProgressDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?ProgressRelationships
    {
        return $this->relationships;
    }
}
