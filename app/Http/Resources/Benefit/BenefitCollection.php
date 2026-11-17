<?php

namespace App\Http\Resources\Benefit;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BenefitCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'benefits' => $this->collection->map(function ($benefit) {
                return [
                    'id' => $benefit->id,
                    'item' => $benefit->item,
                    'name' => $benefit->name,
                    'description' => $benefit->description,
                    'settings' => $benefit->settings,
                    'icon' => $benefit->icon,
                    'created_at' => $this->formatDate($benefit->created_at),
                    'updated_at' => $this->formatDate($benefit->updated_at),
                    'deleted_at' => $this->formatDate($benefit->deleted_at)
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
