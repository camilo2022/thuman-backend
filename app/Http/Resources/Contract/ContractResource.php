<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'contract' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'headquarter_id' => $this->headquarterId()->value(),
                'contract_type_id' => $this->contractTypeId()->value(),
                'position_id' => $this->positionId()->value(),
                'salary' => $this->salary()->value(),
                'transport_allowance' => $this->transportAllowance()->value(),
                'payment_period_id' => $this->paymentPeriodId()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'person_observation' => $this->personObservation()->value(),
                'person_signed_at' => $this->personSignedAt()->value(),
                'person_status' => $this->personStatus()->value(),
                'legal_representative_signed_at' => $this->legalRepresentativeSignedAt()->value(),
                'legal_representative_status' => $this->legalRepresentativeStatus()->value(),
                'settings' => [
                    'accept_terms' => $this->settings()->acceptTerms(),
                    'accept_privacy_policy' => $this->settings()->acceptPrivacyPolicy(),
                    'documents' => $this->settings()->documents()
                ],
                'file_subtypes_id' => $this->fileSubtypesId()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
