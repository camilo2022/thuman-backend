<?php

namespace App\Http\Resources\WorkArrangement;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkArrangementCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'workArrangements' => $this->collection->map(function ($workArrangement) {
                return [
                    'id' => $workArrangement->id,
                    'item' => $workArrangement->item,
                    'name' => $workArrangement->name,
                    'description' => $workArrangement->description,
                    'settings' => $workArrangement->settings,
                    'created_at' => $this->formatDate($workArrangement->created_at),
                    'updated_at' => $this->formatDate($workArrangement->updated_at),
                    'deleted_at' => $this->formatDate($workArrangement->deleted_at)
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
