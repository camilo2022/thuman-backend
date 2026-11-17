<?php

namespace App\Http\Resources\SkillType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SkillTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'skillTypes' => $this->collection->map(function ($skillType) {
                return [
                    'id' => $skillType->id,
                    'item' => $skillType->item,
                    'name' => $skillType->name,
                    'description' => $skillType->description,
                    'settings' => $skillType->settings,
                    'skills' => $skillType->skills,
                    'created_at' => $this->formatDate($skillType->created_at),
                    'updated_at' => $this->formatDate($skillType->updated_at),
                    'deleted_at' => $this->formatDate($skillType->deleted_at)
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
