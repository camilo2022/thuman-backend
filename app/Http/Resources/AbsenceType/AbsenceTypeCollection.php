<?php

namespace App\Http\Resources\AbsenceType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AbsenceTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'absenceTypes' => $this->collection->map(function ($absenceType) {
                return [
                    'id' => $absenceType->id,
                    'item' => $absenceType->item,
                    'name' => $absenceType->name,
                    'description' => $absenceType->description,
                    'settings' => $absenceType->settings,
                    'created_at' => $this->formatDate($absenceType->created_at),
                    'updated_at' => $this->formatDate($absenceType->updated_at),
                    'deleted_at' => $this->formatDate($absenceType->deleted_at)
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
