<?php

namespace App\Http\Resources\Termination;

use App\Models\Termination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TerminationCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'terminations' => $this->collection->map(function ($termination) {
                return [
                    'id' => $termination->id,
                    'person' => $termination->person,
                    'model' => $termination->model,
                    'reason' => $termination->reason,
                    'decision' => $termination->decision,
                    'status' => $termination->status,
                    'date' => $termination->date,
                    'created_at' => $this->formatDate($termination->created_at),
                    'updated_at' => $this->formatDate($termination->updated_at)
                ];
            }),
            'statuses' => Termination::statuses(),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return $date ? Carbon::parse($date)->format($format) : $date;
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
