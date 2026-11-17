<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamMedical\Values;

final class ExamMedicalRelationships
{
    private ?object $application;
    private ?object $city;
    private ?object $health_entity;
    private ?object $epidemiological_program;
    private ?object $file;

    public function __construct(
        ?object $application = null,
        ?object $city = null,
        ?object $health_entity = null,
        ?object $epidemiological_program = null,
        ?object $file = null
    ) {
        $this->application = $application;
        $this->city = $city;
        $this->health_entity = $health_entity;
        $this->epidemiological_program = $epidemiological_program;
        $this->file = $file;
    }

    public function application(): ?object
    {
        return $this->application;
    }

    public function city(): ?object
    {
        return $this->city;
    }

    public function healthEntity(): ?object
    {
        return $this->health_entity;
    }

    public function epidemiologicalProgram(): ?object
    {
        return $this->epidemiological_program;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'city' => $this->city,
            'health_entity' => $this->health_entity,
            'epidemiological_program' => $this->epidemiological_program,
            'file' => $this->file()
        ];
    }
}
