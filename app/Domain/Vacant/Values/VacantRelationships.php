<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

use Illuminate\Support\Collection;

final class VacantRelationships
{
    private ?object $hiring_reason;
    private ?object $position;
    private ?object $person;
    private ?object $headquarter;
    private ?object $contract_type;
    private ?object $work_arrangement;
    private ?object $work_day;
    private Collection $benefits;

    public function __construct(
        ?object $hiring_reason = null,
        ?object $position = null,
        ?object $person = null,
        ?object $headquarter = null,
        ?object $contract_type = null,
        ?object $work_arrangement = null,
        ?object $work_day = null,
        ?Collection $benefits = null
    ) {
        $this->hiring_reason = $hiring_reason;
        $this->position = $position;
        $this->person = $person;
        $this->headquarter = $headquarter;
        $this->contract_type = $contract_type;
        $this->work_arrangement = $work_arrangement;
        $this->work_day = $work_day;
        $this->benefits = $benefits ?? collect();
    }

    public function hiringReason(): ?object
    {
        return $this->hiring_reason;
    }

    public function position(): ?object
    {
        return $this->position;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function headquarter(): ?object
    {
        return $this->headquarter;
    }

    public function contractType(): ?object
    {
        return $this->contract_type;
    }

    public function workArrangement(): ?object
    {
        return $this->work_arrangement;
    }

    public function workDay(): ?object
    {
        return $this->work_day;
    }

    public function benefits(): Collection
    {
        return $this->benefits;
    }

    public function toArray(): array
    {
        return [
            'hiring_reason' => $this->hiring_reason,
            'position' => $this->position,
            'person' => $this->person,
            'headquarter' => $this->headquarter,
            'contract_type' => $this->contract_type,
            'work_arrangement' => $this->work_arrangement,
            'work_day' => $this->work_day,
            'benefits' => $this->benefits
        ];
    }
}
