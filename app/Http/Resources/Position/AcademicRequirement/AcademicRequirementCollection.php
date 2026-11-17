<?php

namespace App\Http\Resources\Position\AcademicRequirement;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AcademicRequirementCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'academicRequirements' => $this->collection->map(function ($academicRequirement) {
                return [
                    'id' => $academicRequirement->id,
                    'position' => $academicRequirement->position,
                    'academic_level' => $academicRequirement->academic_level,
                    'academic_title' => $academicRequirement->academic_title,
                    'formation_status' => $academicRequirement->formation_status,
                    'created_at' => $this->formatDate($academicRequirement->created_at),
                    'updated_at' => $this->formatDate($academicRequirement->updated_at)
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
