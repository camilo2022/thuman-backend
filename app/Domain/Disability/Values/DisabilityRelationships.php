<?php

declare(strict_types=1);

namespace App\Domain\Disability\Values;

final class DisabilityRelationships
{
    private ?object $person;
    private ?object $disability_type;
    private ?object $cie_code;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $disability_type = null,
        ?object $cie_code = null,
        ?object $file = null,
    ) {
        $this->person = $person;
        $this->disability_type = $disability_type;
        $this->cie_code = $cie_code;
        $this->file = $file;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function disabilityType(): ?object
    {
        return $this->disability_type;
    }

    public function cieCode(): ?object
    {
        return $this->cie_code ? (object) array_merge($this->cie_code->toArray(), [
                'cie_subgroup' => $this->cie_code?->cie_subgroup?->first() ? (object) array_merge($this->cie_code->cie_subgroup->first()->toArray(), [
                    'cie_group' => $this->cie_code->cie_subgroup->first()?->cie_group?->first() ? (object) array_merge($this->cie_code->cie_subgroup->first()->cie_group->first()->toArray(), [
                        'cie_chapter' => $this->cie_code->cie_subgroup->first()?->cie_group?->first()?->cie_chapter?->first(),
                    ]) : null,
                ]) : null,
            ]) : null;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'disability_type' => $this->disability_type,
            'cie_code' => $this->cieCode(),
            'file' => $this->file
        ];
    }
}
