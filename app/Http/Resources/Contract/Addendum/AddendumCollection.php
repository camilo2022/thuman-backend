<?php

namespace App\Http\Resources\Contract\Addendum;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddendumCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'addendums' => $this->collection->map(function ($addendum) {
                return [
                    'id' => $addendum->id,
                    'contract' => $addendum->contract,
                    'addendum_type' => $addendum->addendum_type,
                    'headquarter' => $addendum->headquarter,
                    'contract_type' => $addendum->contract_type,
                    'position' => $addendum->position,
                    'salary' => $addendum->salary,
                    'payment_period' => $addendum->payment_period,
                    'start_date' => $addendum->start_date,
                    'end_date' => $addendum->end_date,
                    'person_observation' => $addendum->person_observation,
                    'person_signed_at' => $addendum->person_signed_at,
                    'person_status' => $addendum->person_status,
                    'legal_representative_signed_at' => $addendum->legal_representative_signed_at,
                    'legal_representative_status' => $addendum->legal_representative_status,
                    'settings' => $addendum->settings,
                    'file_subtypes' => $addendum->file_subtypes,
                    'person_signed' => $addendum->person_signed,
                    'legal_representative_signed' => $addendum->legal_representative_signed,
                    'created_at' => $this->formatDate($addendum->created_at),
                    'updated_at' => $this->formatDate($addendum->updated_at),
                    'deleted_at' => $this->formatDate($addendum->deleted_at)
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
