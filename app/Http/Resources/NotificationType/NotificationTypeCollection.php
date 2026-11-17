<?php

namespace App\Http\Resources\NotificationType;

use App\Models\NotificationChannel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'notificationTypes' => $this->collection->map(function ($notificationType) {
                return [
                    'id' => $notificationType->id,
                    'item' => $notificationType->item,
                    'name' => $notificationType->name,
                    'description' => $notificationType->description,
                    'settings' => array_merge(
                        (array) $notificationType->settings,
                        [
                            'channels' => collect($notificationType->settings->channels ?? [])
                                ->map(fn ($channel) => NotificationChannel::find($channel?->notification_channel_id))
                                ->filter()
                                ->map(fn ($channel) => [
                                    'id' => $channel->id,
                                    'name' => $channel->name,
                                    'description' => $channel->description,
                                    'settings' => $channel->settings,
                                ])
                                ->values()
                        ]
                    ),
                    'created_at' => $this->formatDate($notificationType->created_at),
                    'updated_at' => $this->formatDate($notificationType->updated_at),
                    'deleted_at' => $this->formatDate($notificationType->deleted_at)
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
