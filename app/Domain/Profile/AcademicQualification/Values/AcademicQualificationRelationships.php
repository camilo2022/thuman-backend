<?php

declare(strict_types=1);

namespace App\Domain\Profile\AcademicQualification\Values;

final class AcademicQualificationRelationships
{
    private ?object $profile;
    private ?object $city;
    private ?object $academic_level;
    private ?object $formation_status;
    private ?object $file;

    public function __construct(
        ?object $profile = null,
        ?object $city = null,
        ?object $academic_level = null,
        ?object $formation_status = null,
        ?object $file = null
    ) {
        $this->profile = $profile;
        $this->city = $city;
        $this->academic_level = $academic_level;
        $this->formation_status = $formation_status;
        $this->file = $file;
    }

    public function profile(): ?object
    {
        return $this->profile;
    }

    public function city(): ?object
    {
        return $this->city;
    }

    public function academicLevel(): ?object
    {
        return $this->academic_level;
    }

    public function formationStatus(): ?object
    {
        return $this->formation_status;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'profile' => $this->profile,
            'city' => $this->city,
            'academic_level' => $this->academic_level,
            'formation_status' => $this->formation_status,
            'file' => $this->file
        ];
    }
}
