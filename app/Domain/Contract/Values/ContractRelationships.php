<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

use Illuminate\Support\Collection;

final class ContractRelationships
{
    private ?object $application;
    private ?object $person;
    private ?object $headquarter;
    private ?object $contract_type;
    private ?object $position;
    private ?object $payment_period;
    private ?object $signed_person;
    private ?object $signed_legal_representative;
    private ?object $pdf;
    private Collection $file_subtypes_id;

    public function __construct(
        ?object $application = null,
        ?object $person = null,
        ?object $headquarter = null,
        ?object $contract_type = null,
        ?object $position = null,
        ?object $payment_period = null,
        ?object $signed_person = null,
        ?object $signed_legal_representative = null,
        ?object $pdf = null,
        ?Collection $file_subtypes_id = null
    ) {
        $this->application = $application;
        $this->person = $person;
        $this->headquarter = $headquarter;
        $this->contract_type = $contract_type;
        $this->position = $position;
        $this->payment_period = $payment_period;
        $this->signed_person = $signed_person;
        $this->signed_legal_representative = $signed_legal_representative;
        $this->pdf = $pdf;
        $this->file_subtypes_id = $file_subtypes_id ?? collect();
    }

    public function application(): ?object
    {
        return $this->application;
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

    public function position(): ?object
    {
        return $this->position;
    }

    public function paymentPeriod(): ?object
    {
        return $this->payment_period;
    }

    public function signedPerson(): ?object
    {
        return $this->signed_person;
    }

    public function signedLegalRepresentative(): ?object
    {
        return $this->signed_legal_representative;
    }

    public function pdf(): ?object
    {
        return $this->pdf;
    }

    public function fileSubtypesId(): Collection
    {
        return $this->file_subtypes_id;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'person' => $this->person,
            'headquarter' => $this->headquarter,
            'contract_type' => $this->contract_type,
            'position' => $this->position,
            'payment_period' => $this->payment_period,
            'signed_person' => $this->signed_person,
            'signed_legal_representative' => $this->signed_legal_representative,
            'pdf' => $this->pdf,
            'file_subtypes_id' => $this->file_subtypes_id
        ];
    }
}
