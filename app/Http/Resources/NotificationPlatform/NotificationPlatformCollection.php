<?php

namespace App\Http\Resources\NotificationPlatform;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationPlatformCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'notificationPlatforms' => $this->collection->map(function ($notificationPlatform) {
                return [
                    'id' => $notificationPlatform->id,
                    'item' => $notificationPlatform->item,
                    'name' => $notificationPlatform->name,
                    'description' => $notificationPlatform->description,
                    'settings' => $notificationPlatform->settings,
                    'created_at' => $this->formatDate($notificationPlatform->created_at),
                    'updated_at' => $this->formatDate($notificationPlatform->updated_at),
                    'deleted_at' => $this->formatDate($notificationPlatform->deleted_at)
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
