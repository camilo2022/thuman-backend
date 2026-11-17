<?php

declare(strict_types=1);

namespace App\Domain\Profile\ProfessionalExperience\Values;

final class ProfessionalExperienceRelationships
{
    private ?object $profile;
    private ?object $work_arrangement;
    private ?object $supervisor_dialing_country;
    private ?object $city;
    private ?object $file;

    public function __construct(
        ?object $profile = null,
        ?object $work_arrangement = null,
        ?object $supervisor_dialing_country = null,
        ?object $city = null,
        ?object $file = null
    ) {
        $this->profile = $profile;
        $this->work_arrangement = $work_arrangement;
        $this->supervisor_dialing_country = $supervisor_dialing_country;
        $this->city = $city;
        $this->file = $file;
    }

    public function profile(): ?object
    {
        return $this->profile;
    }

    public function workArrangement(): ?object
    {
        return $this->work_arrangement;
    }

    public function supervisorDialingCountry(): ?object
    {
        return $this->supervisor_dialing_country;
    }

    public function city(): ?object
    {
        return $this->city;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'profile' => $this->profile,
            'work_arrangement' => $this->work_arrangement,
            'supervisor_dialing_country' => $this->supervisor_dialing_country,
            'city' => $this->city,
            'file' => $this->file
        ];
    }
}
