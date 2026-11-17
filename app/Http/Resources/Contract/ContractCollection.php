<?php

namespace App\Http\Resources\Contract;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'contracts' => $this->collection->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'person' => $contract->person,
                    'headquarter' => $contract->headquarter,
                    'contract_type' => $contract->contract_type,
                    'position' => $contract->position,
                    'salary' => $contract->salary,
                    'transport_allowance' => $contract->transport_allowance,
                    'payment_period' => $contract->payment_period,
                    'start_date' => $contract->start_date,
                    'end_date' => $contract->end_date,
                    'person_observation' => $contract->person_observation,
                    'person_signed_at' => $contract->person_signed_at,
                    'person_status' => $contract->person_status,
                    'legal_representative_signed_at' => $contract->legal_representative_signed_at,
                    'legal_representative_status' => $contract->legal_representative_status,
                    'settings' => $contract->settings,
                    'file_subtypes' => $contract->file_subtypes,
                    'person_signed' => $contract->person_signed,
                    'legal_representative_signed' => $contract->legal_representative_signed,
                    'created_at' => $this->formatDate($contract->created_at),
                    'updated_at' => $this->formatDate($contract->updated_at),
                    'deleted_at' => $this->formatDate($contract->deleted_at)
                ];
            }),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : $date;
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }
}
