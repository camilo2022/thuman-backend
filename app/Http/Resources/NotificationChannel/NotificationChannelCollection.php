<?php

namespace App\Http\Resources\NotificationChannel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationChannelCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'notificationChannels' => $this->collection->map(function ($notificationChannel) {
                return [
                    'id' => $notificationChannel->id,
                    'item' => $notificationChannel->item,
                    'name' => $notificationChannel->name,
                    'description' => $notificationChannel->description,
                    'settings' => $notificationChannel->settings,
                    'created_at' => $this->formatDate($notificationChannel->created_at),
                    'updated_at' => $this->formatDate($notificationChannel->updated_at),
                    'deleted_at' => $this->formatDate($notificationChannel->deleted_at)
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
