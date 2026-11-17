<?php

namespace App\Http\Resources\ContractType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'contractType' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => [
                    'health_entity_affiliation' => $this->settings()->healthEntityAffiliation(),
                    'pension_fund_affiliation' => $this->settings()->pensionFundAffiliation(),
                    'compensation_fund_affiliation' => $this->settings()->compensationFundAffiliations(),
                    'risk_manager_affiliation' => $this->settings()->riskManagerAffiliation(),
                    'bank_certificate' => $this->settings()->bankCertificate(),
                    'fiscal_document_certificate' => $this->settings()->fiscalDocumentCertificate(),
                    'requires_end_date' => $this->settings()->requiresEndDate(),
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
