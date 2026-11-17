<?php

namespace App\Http\Resources\TerminationType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TerminationTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'terminationTypes' => $this->collection->map(function ($terminationType) {
                return [
                    'id' => $terminationType->id,
                    'item' => $terminationType->item,
                    'name' => $terminationType->name,
                    'description' => $terminationType->description,
                    'settings' => $terminationType->settings,
                    'created_at' => $this->formatDate($terminationType->created_at),
                    'updated_at' => $this->formatDate($terminationType->updated_at),
                    'deleted_at' => $this->formatDate($terminationType->deleted_at)
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
