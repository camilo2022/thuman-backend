<?php

namespace App\Http\Resources\Contract\Addendum;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddendumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'addendum' => [
                'id' => $this->id()->value(),
                'contract_id' => $this->contractId()->value(),
                'addendum_type_id' => $this->addendumTypeId()->value(),
                'contract_type_id' => $this->contractTypeId()->value(),
                'position_id' => $this->positionId()->value(),
                'salary' => $this->salary()->value(),
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
                    'templates' => $this->settings()->templates()
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
