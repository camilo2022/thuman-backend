<?php

namespace App\Http\Resources\TerminationType\Reason;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReasonCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'reasons' => $this->collection->map(function ($reason) {
                return [
                    'id' => $reason->id,
                    'item' => $reason->item,
                    'termination_type' => $reason->termination_type->first(),
                    'name' => $reason->name,
                    'description' => $reason->description,
                    'settings' => $reason->settings,
                    'created_at' => $this->formatDate($reason->created_at),
                    'updated_at' => $this->formatDate($reason->updated_at),
                    'deleted_at' => $this->formatDate($reason->deleted_at)
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
