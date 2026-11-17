<?php

namespace App\Http\Resources\Position\RequiredSkill;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RequiredSkillCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'requiredSkills' => $this->collection->map(function ($requiredSkill) {
                return [
                    'id' => $requiredSkill->id,
                    'position' => $requiredSkill->position,
                    'skill' => $requiredSkill->skill,
                    'percentage' => $requiredSkill->percentage,
                    'created_at' => $this->formatDate($requiredSkill->created_at),
                    'updated_at' => $this->formatDate($requiredSkill->updated_at)
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
