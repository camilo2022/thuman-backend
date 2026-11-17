<?php

namespace App\Http\Resources\LanguageLevel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LanguageLevelCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'languageLevels' => $this->collection->map(function ($languageLevel) {
                return [
                    'id' => $languageLevel->id,
                    'item' => $languageLevel->item,
                    'name' => $languageLevel->name,
                    'description' => $languageLevel->description,
                    'settings' => $languageLevel->settings,
                    'created_at' => $this->formatDate($languageLevel->created_at),
                    'updated_at' => $this->formatDate($languageLevel->updated_at),
                    'deleted_at' => $this->formatDate($languageLevel->deleted_at)
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
