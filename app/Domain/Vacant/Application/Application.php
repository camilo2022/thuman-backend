<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application;

use App\Domain\Vacant\Application\Values\ApplicationAnswers;
use App\Domain\Vacant\Application\Values\ApplicationCreatedAt;
use App\Domain\Vacant\Application\Values\ApplicationFormId;
use App\Domain\Vacant\Application\Values\ApplicationPersonId;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Domain\Vacant\Application\Values\ApplicationObservation;
use App\Domain\Vacant\Application\Values\ApplicationQuestions;
use App\Domain\Vacant\Application\Values\ApplicationVacantId;
use App\Domain\Vacant\Application\Values\ApplicationRelationships;
use App\Domain\Vacant\Application\Values\ApplicationSalary;
use App\Domain\Vacant\Application\Values\ApplicationStatus;
use App\Domain\Vacant\Application\Values\ApplicationUpdatedAt;

final class Application
{
    private $id;
    private $personId;
    private $vacantId;
    private $formId;
    private $questions;
    private $answers;
    private $salary;
    private $observation;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?ApplicationId $id,
        ApplicationPersonId $personId,
        ApplicationVacantId $vacantId,
        ApplicationFormId $formId,
        ApplicationQuestions $questions,
        ApplicationAnswers $answers,
        ApplicationSalary $salary,
        ApplicationObservation $observation,
        ?ApplicationStatus $status = null,
        ?ApplicationCreatedAt $createdAt = null,
        ?ApplicationUpdatedAt $updatedAt = null,
        ?ApplicationRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->vacantId = $vacantId;
        $this->formId = $formId;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->salary = $salary;
        $this->observation = $observation;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ApplicationId
    {
        return $this->id;
    }

    public function personId(): ApplicationPersonId
    {
        return $this->personId;
    }

    public function vacantId(): ApplicationVacantId
    {
        return $this->vacantId;
    }

    public function formId(): ApplicationFormId
    {
        return $this->formId;
    }

    public function questions(): ApplicationQuestions
    {
        return $this->questions;
    }

    public function answers(): ApplicationAnswers
    {
        return $this->answers;
    }

    public function salary(): ApplicationSalary
    {
        return $this->salary;
    }

    public function observation(): ApplicationObservation
    {
        return $this->observation;
    }

    public function status(): ApplicationStatus
    {
        return $this->status;
    }

    public function createdAt(): ?ApplicationCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ApplicationUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?ApplicationRelationships
    {
        return $this->relationships;
    }
}
