<?php

namespace App\Http\Resources\PaymentPeriod;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentPeriodCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'paymentPeriods' => $this->collection->map(function ($paymentPeriod) {
                return [
                    'id' => $paymentPeriod->id,
                    'item' => $paymentPeriod->item,
                    'name' => $paymentPeriod->name,
                    'description' => $paymentPeriod->description,
                    'settings' => $paymentPeriod->settings,
                    'created_at' => $this->formatDate($paymentPeriod->created_at),
                    'updated_at' => $this->formatDate($paymentPeriod->updated_at),
                    'deleted_at' => $this->formatDate($paymentPeriod->deleted_at)
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
