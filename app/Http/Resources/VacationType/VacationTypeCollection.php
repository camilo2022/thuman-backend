<?php

namespace App\Http\Resources\VacationType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VacationTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'vacationTypes' => $this->collection->map(function ($vacationType) {
                return [
                    'id' => $vacationType->id,
                    'item' => $vacationType->item,
                    'name' => $vacationType->name,
                    'description' => $vacationType->description,
                    'settings' => $vacationType->settings,
                    'created_at' => $this->formatDate($vacationType->created_at),
                    'updated_at' => $this->formatDate($vacationType->updated_at),
                    'deleted_at' => $this->formatDate($vacationType->deleted_at)
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
