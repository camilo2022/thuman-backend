<?php

namespace App\Http\Resources\Vacation;

use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VacationCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'vacations' => $this->collection->map(function ($vacation) {
                return [
                    'id' => $vacation->id,
                    'person' => $vacation->person,
                    'vacation_type' => $vacation->vacation_type,
                    'period' => $vacation->period,
                    'days' => $vacation->days,
                    'start_date' => $this->formatDate($vacation->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($vacation->end_date, 'Y-m-d'),
                    'status' => $vacation->status,
                    'created_at' => $this->formatDate($vacation->created_at),
                    'updated_at' => $this->formatDate($vacation->updated_at)
                ];
            }),
            'statuses' => Vacation::statuses(),
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
