<?php

namespace App\Http\Resources\Induction\Step;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StepCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'steps' => $this->collection->map(function ($step) {
                return [
                    'id' => $step->id,
                    'induction' => $step->induction,
                    'name' => $step->name,
                    'description' => $step->description,
                    'form' => $step->form,
                    'video' => $step->video,
                    'progress' => $step->progress,
                    'created_at' => $this->formatDate($step->created_at),
                    'updated_at' => $this->formatDate($step->updated_at),
                    'deleted_at' => $this->formatDate($step->deleted_at)
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
