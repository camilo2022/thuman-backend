<?php

namespace App\Http\Resources\Position\RequiredExperience;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RequiredExperienceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'requiredExperiences' => $this->collection->map(function ($requiredExperience) {
                return [
                    'id' => $requiredExperience->id,
                    'position' => $requiredExperience->position,
                    'name' => $requiredExperience->name,
                    'duration_months' => $requiredExperience->duration_months,
                    'description' => $requiredExperience->description,
                    'created_at' => $this->formatDate($requiredExperience->created_at),
                    'updated_at' => $this->formatDate($requiredExperience->updated_at)
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
