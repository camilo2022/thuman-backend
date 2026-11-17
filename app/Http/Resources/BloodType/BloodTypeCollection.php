<?php

namespace App\Http\Resources\BloodType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BloodTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'bloodTypes' => $this->collection->map(function ($bloodType) {
                return [
                    'id' => $bloodType->id,
                    'item' => $bloodType->item,
                    'name' => $bloodType->name,
                    'description' => $bloodType->description,
                    'settings' => $bloodType->settings,
                    'created_at' => $this->formatDate($bloodType->created_at),
                    'updated_at' => $this->formatDate($bloodType->updated_at),
                    'deleted_at' => $this->formatDate($bloodType->deleted_at)
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
