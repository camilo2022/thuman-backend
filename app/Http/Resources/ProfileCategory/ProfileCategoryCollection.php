<?php

namespace App\Http\Resources\ProfileCategory;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfileCategoryCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'profileCategories' => $this->collection->map(function ($profileCategory) {
                return [
                    'id' => $profileCategory->id,
                    'item' => $profileCategory->item,
                    'name' => $profileCategory->name,
                    'description' => $profileCategory->description,
                    'settings' => $profileCategory->settings,
                    'created_at' => $this->formatDate($profileCategory->created_at),
                    'updated_at' => $this->formatDate($profileCategory->updated_at),
                    'deleted_at' => $this->formatDate($profileCategory->deleted_at)
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
