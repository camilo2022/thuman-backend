<?php

namespace App\Http\Resources\Profile\ProfessionalSkill;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfessionalSkillCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'professionalSkills' => $this->collection->map(function ($professionalSkill) {
                return [
                    'id' => $professionalSkill->id,
                    'profile' => $professionalSkill->profile,
                    'skill' => $professionalSkill->skill,
                    'percentage' => $professionalSkill->percentage,
                    'created_at' => $this->formatDate($professionalSkill->created_at),
                    'updated_at' => $this->formatDate($professionalSkill->updated_at)
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
