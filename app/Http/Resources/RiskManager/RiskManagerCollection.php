<?php

namespace App\Http\Resources\RiskManager;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RiskManagerCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'riskManagers' => $this->collection->map(function ($riskManager) {
                return [
                    'id' => $riskManager->id,
                    'item' => $riskManager->item,
                    'name' => $riskManager->name,
                    'description' => $riskManager->description,
                    'settings' => $riskManager->settings,
                    'created_at' => $this->formatDate($riskManager->created_at),
                    'updated_at' => $this->formatDate($riskManager->updated_at),
                    'deleted_at' => $this->formatDate($riskManager->deleted_at)
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
