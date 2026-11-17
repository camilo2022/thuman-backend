<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

use Illuminate\Support\Collection;

final class AddendumRelationships
{
    private ?object $contract;
    private ?object $addendum_type;
    private ?object $headquarter;
    private ?object $contract_type;
    private ?object $position;
    private ?object $payment_period;
    private ?object $signed_person;
    private ?object $signed_legal_representative;
    private Collection $file_subtypes_id;

    public function __construct(
        ?object $contract = null,
        ?object $addendum_type = null,
        ?object $headquarter = null,
        ?object $contract_type = null,
        ?object $position = null,
        ?object $payment_period = null,
        ?object $signed_person = null,
        ?object $signed_legal_representative = null,
        ?Collection $file_subtypes_id = null
    ) {
        $this->contract = $contract;
        $this->addendum_type = $addendum_type;
        $this->headquarter = $headquarter;
        $this->contract_type = $contract_type;
        $this->position = $position;
        $this->payment_period = $payment_period;
        $this->signed_person = $signed_person;
        $this->signed_legal_representative = $signed_legal_representative;
        $this->file_subtypes_id = $file_subtypes_id ?? collect();
    }

    public function contract(): ?object
    {
        return $this->contract;
    }

    public function addendumType(): ?object
    {
        return $this->addendum_type;
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

    public function fileSubtypesId(): Collection
    {
        return $this->file_subtypes_id;
    }

    public function toArray(): array
    {
        return [
            'contract' => $this->contract,
            'addendum_type' => $this->addendum_type,
            'headquarter' => $this->headquarter,
            'contract_type' => $this->contract_type,
            'position' => $this->position,
            'payment_period' => $this->payment_period,
            'signed_person' => $this->signed_person,
            'signed_legal_representative' => $this->signed_legal_representative,
            'file_subtypes_id' => $this->file_subtypes_id
        ];
    }
}
