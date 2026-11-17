<?php

namespace App\Http\Resources\PensionFund;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PensionFundCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'pensionFunds' => $this->collection->map(function ($pensionFund) {
                return [
                    'id' => $pensionFund->id,
                    'item' => $pensionFund->item,
                    'name' => $pensionFund->name,
                    'description' => $pensionFund->description,
                    'settings' => $pensionFund->settings,
                    'created_at' => $this->formatDate($pensionFund->created_at),
                    'updated_at' => $this->formatDate($pensionFund->updated_at),
                    'deleted_at' => $this->formatDate($pensionFund->deleted_at)
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
