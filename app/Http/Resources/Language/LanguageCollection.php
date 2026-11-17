<?php

namespace App\Http\Resources\Language;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LanguageCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'languages' => $this->collection->map(function ($language) {
                return [
                    'id' => $language->id,
                    'item' => $language->item,
                    'name' => $language->name,
                    'description' => $language->description,
                    'settings' => $language->settings,
                    'created_at' => $this->formatDate($language->created_at),
                    'updated_at' => $this->formatDate($language->updated_at),
                    'deleted_at' => $this->formatDate($language->deleted_at)
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
