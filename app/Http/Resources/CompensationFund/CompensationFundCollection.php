<?php

namespace App\Http\Resources\CompensationFund;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CompensationFundCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'compensationFunds' => $this->collection->map(function ($compensationFund) {
                return [
                    'id' => $compensationFund->id,
                    'item' => $compensationFund->item,
                    'name' => $compensationFund->name,
                    'description' => $compensationFund->description,
                    'settings' => $compensationFund->settings,
                    'created_at' => $this->formatDate($compensationFund->created_at),
                    'updated_at' => $this->formatDate($compensationFund->updated_at),
                    'deleted_at' => $this->formatDate($compensationFund->deleted_at)
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
