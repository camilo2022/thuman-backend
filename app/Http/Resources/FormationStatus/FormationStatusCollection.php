<?php

namespace App\Http\Resources\FormationStatus;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FormationStatusCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'formationStatuses' => $this->collection->map(function ($formationStatus) {
                return [
                    'id' => $formationStatus->id,
                    'item' => $formationStatus->item,
                    'name' => $formationStatus->name,
                    'description' => $formationStatus->description,
                    'settings' => $formationStatus->settings,
                    'created_at' => $this->formatDate($formationStatus->created_at),
                    'updated_at' => $this->formatDate($formationStatus->updated_at),
                    'deleted_at' => $this->formatDate($formationStatus->deleted_at)
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
