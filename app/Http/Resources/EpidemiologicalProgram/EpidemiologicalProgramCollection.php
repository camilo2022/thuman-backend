<?php

namespace App\Http\Resources\EpidemiologicalProgram;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EpidemiologicalProgramCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'epidemiologicalPrograms' => $this->collection->map(function ($epidemiologicalProgram) {
                return [
                    'id' => $epidemiologicalProgram->id,
                    'item' => $epidemiologicalProgram->item,
                    'name' => $epidemiologicalProgram->name,
                    'description' => $epidemiologicalProgram->description,
                    'settings' => $epidemiologicalProgram->settings,
                    'created_at' => $this->formatDate($epidemiologicalProgram->created_at),
                    'updated_at' => $this->formatDate($epidemiologicalProgram->updated_at),
                    'deleted_at' => $this->formatDate($epidemiologicalProgram->deleted_at)
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
