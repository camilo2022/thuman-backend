<?php

namespace App\Http\Resources\Absence;

use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AbsenceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'absences' => $this->collection->map(function ($absence) {
                return [
                    'id' => $absence->id,
                    'person' => $absence->person,
                    'absence_type' => $absence->absence_type,
                    'start_date' => $this->formatDate($absence->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($absence->end_date, 'Y-m-d'),
                    'reason' => $absence->reason,
                    'compensation' => $absence->compensation,
                    'status' => $absence->status,
                    'created_at' => $this->formatDate($absence->created_at),
                    'updated_at' => $this->formatDate($absence->updated_at)
                ];
            }),
            'statuses' => Absence::statuses(),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return $date ? Carbon::parse($date)->format($format) : $date;
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
