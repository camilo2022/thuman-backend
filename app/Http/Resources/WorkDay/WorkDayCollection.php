<?php

namespace App\Http\Resources\WorkDay;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkDayCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'workDays' => $this->collection->map(function ($workDay) {
                return [
                    'id' => $workDay->id,
                    'item' => $workDay->item,
                    'name' => $workDay->name,
                    'description' => $workDay->description,
                    'settings' => $workDay->settings,
                    'created_at' => $this->formatDate($workDay->created_at),
                    'updated_at' => $this->formatDate($workDay->updated_at),
                    'deleted_at' => $this->formatDate($workDay->deleted_at)
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
