<?php

declare(strict_types=1);

namespace App\Domain\Survey;

use App\Domain\Survey\Values\SurveyAnswers;
use App\Domain\Survey\Values\SurveyCreatedAt;
use App\Domain\Survey\Values\SurveyId;
use App\Domain\Survey\Values\SurveyFormId;
use App\Domain\Survey\Values\SurveyPersonId;
use App\Domain\Survey\Values\SurveyQuestions;
use App\Domain\Survey\Values\SurveyRelationships;
use App\Domain\Survey\Values\SurveyUpdatedAt;

final class Survey
{
    private $id;
    private $formId;
    private $questions;
    private $answers;
    private $personId;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?SurveyId $id,
        SurveyFormId $formId,
        SurveyQuestions $questions,
        SurveyAnswers $answers,
        SurveyPersonId $personId,
        ?SurveyCreatedAt $createdAt = null,
        ?SurveyUpdatedAt $updatedAt = null,
        ?SurveyRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->formId = $formId;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->personId = $personId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?SurveyId
    {
        return $this->id;
    }

    public function formId(): SurveyFormId
    {
        return $this->formId;
    }

    public function questions(): SurveyQuestions
    {
        return $this->questions;
    }

    public function answers(): SurveyAnswers
    {
        return $this->answers;
    }

    public function personId(): SurveyPersonId
    {
        return $this->personId;
    }

    public function createdAt(): ?SurveyCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?SurveyUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?SurveyRelationships
    {
        return $this->relationships;
    }
}
