<?php

namespace App\Http\Resources\AcademicLevel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AcademicLevelCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'academicLevels' => $this->collection->map(function ($academicLevel) {
                return [
                    'id' => $academicLevel->id,
                    'item' => $academicLevel->item,
                    'name' => $academicLevel->name,
                    'description' => $academicLevel->description,
                    'settings' => $academicLevel->settings,
                    'created_at' => $this->formatDate($academicLevel->created_at),
                    'updated_at' => $this->formatDate($academicLevel->updated_at),
                    'deleted_at' => $this->formatDate($academicLevel->deleted_at)
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
