<?php

namespace App\Http\Resources\FiscalDocumentCertificate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FiscalDocumentCertificateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'fiscalDocumentCertificate' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'country_id' => $this->countryId()->value(),
                'fiscal_document_id' => $this->fiscalDocumentId()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
