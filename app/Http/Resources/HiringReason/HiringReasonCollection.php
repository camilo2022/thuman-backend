<?php

namespace App\Http\Resources\HiringReason;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HiringReasonCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'hiringReasons' => $this->collection->map(function ($hiringReason) {
                return [
                    'id' => $hiringReason->id,
                    'item' => $hiringReason->item,
                    'name' => $hiringReason->name,
                    'description' => $hiringReason->description,
                    'settings' => $hiringReason->settings,
                    'created_at' => $this->formatDate($hiringReason->created_at),
                    'updated_at' => $this->formatDate($hiringReason->updated_at),
                    'deleted_at' => $this->formatDate($hiringReason->deleted_at)
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
