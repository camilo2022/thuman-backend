<?php

namespace App\Http\Resources\Skill;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SkillCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'skills' => $this->collection->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'item' => $skill->item,
                    'name' => $skill->name,
                    'description' => $skill->description,
                    'settings' => $skill->settings,
                    'skill_type' => $skill->skill_type->first(),
                    'created_at' => $this->formatDate($skill->created_at),
                    'updated_at' => $this->formatDate($skill->updated_at),
                    'deleted_at' => $this->formatDate($skill->deleted_at)
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
