<?php

namespace App\Http\Resources\Enterprise\Entity\Ally\Campaign;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CampaignCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'campaigns' => $this->collection->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'ally' => $campaign->ally,
                    'name' => $campaign->name,
                    'description' => $campaign->description,
                    'created_at' => $this->formatDate($campaign->created_at),
                    'updated_at' => $this->formatDate($campaign->updated_at),
                    'deleted_at' => $this->formatDate($campaign->deleted_at)
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
