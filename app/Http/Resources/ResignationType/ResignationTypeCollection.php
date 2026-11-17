<?php

namespace App\Http\Resources\ResignationType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResignationTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'resignationTypes' => $this->collection->map(function ($resignationType) {
                return [
                    'id' => $resignationType->id,
                    'item' => $resignationType->item,
                    'name' => $resignationType->name,
                    'description' => $resignationType->description,
                    'settings' => $resignationType->settings,
                    'created_at' => $this->formatDate($resignationType->created_at),
                    'updated_at' => $this->formatDate($resignationType->updated_at),
                    'deleted_at' => $this->formatDate($resignationType->deleted_at)
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
