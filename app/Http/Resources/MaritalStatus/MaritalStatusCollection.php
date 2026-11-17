<?php

namespace App\Http\Resources\MaritalStatus;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MaritalStatusCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'maritalStatuses' => $this->collection->map(function ($maritalStatus) {
                return [
                    'id' => $maritalStatus->id,
                    'item' => $maritalStatus->item,
                    'name' => $maritalStatus->name,
                    'description' => $maritalStatus->description,
                    'settings' => $maritalStatus->settings,
                    'created_at' => $this->formatDate($maritalStatus->created_at),
                    'updated_at' => $this->formatDate($maritalStatus->updated_at),
                    'deleted_at' => $this->formatDate($maritalStatus->deleted_at)
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
