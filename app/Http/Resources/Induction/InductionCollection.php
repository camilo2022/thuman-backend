<?php

namespace App\Http\Resources\Induction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InductionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'inductions' => $this->collection->map(function ($induction) {
                return [
                    'id' => $induction->id,
                    'name' => $induction->name,
                    'description' => $induction->description,
                    'assignable_type' => $induction->assignable_type,
                    'assignable' => $induction->assignable,
                    'model_type' => $induction->model_type,
                    'model' => $induction->model,
                    'steps_total' => $induction->steps_total ?? 0,
                    'steps_finished' => $induction->steps_finished ?? 0,
                    'created_at' => $this->formatDate($induction->created_at),
                    'updated_at' => $this->formatDate($induction->updated_at),
                    'deleted_at' => $this->formatDate($induction->deleted_at)
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
