<?php

namespace App\Http\Resources\BankCertificate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankCertificateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bankCertificate' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'country_id' => $this->countryId()->value(),
                'bank_id' => $this->bankId()->value(),
                'bank_account_type_id' => $this->bankAccountTypeId()->value(),
                'account_number' => $this->accountNumber()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
