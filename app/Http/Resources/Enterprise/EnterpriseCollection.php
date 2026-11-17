<?php

namespace App\Http\Resources\Enterprise;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EnterpriseCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'enterprises' => $this->collection->map(function ($enterprise) {
                return [
                    'id' => $enterprise->id,
                    'name' => $enterprise->name,
                    'description' => $enterprise->description,
                    'entities' => $enterprise->entities,
                    'logo' => $enterprise->logo,
                    'created_at' => $this->formatDate($enterprise->created_at),
                    'updated_at' => $this->formatDate($enterprise->updated_at),
                    'deleted_at' => $this->formatDate($enterprise->deleted_at)
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
