<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\NotificationChannel\Contracts\NotificationChannelContract;
use App\Models\NotificationChannel as NotificationChannelModel;
use App\Domain\NotificationChannel\NotificationChannel;
use App\Domain\NotificationChannel\Values\NotificationChannelCreatedAt;
use App\Domain\NotificationChannel\Values\NotificationChannelDeletedAt;
use App\Domain\NotificationChannel\Values\NotificationChannelId;
use App\Domain\NotificationChannel\Values\NotificationChannelName;
use App\Domain\NotificationChannel\Values\NotificationChannelDescription;
use App\Domain\NotificationChannel\Values\NotificationChannelItemId;
use App\Domain\NotificationChannel\Values\NotificationChannelSettings;
use App\Domain\NotificationChannel\Values\NotificationChannelUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class NotificationChannelRepository implements NotificationChannelContract
{
    private $notificationChannelModel;

    public function __construct()
    {
        $this->notificationChannelModel = new NotificationChannelModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $notificationChannels = $this->notificationChannelModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 23)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $notificationChannels->paginate($perPage ?? $notificationChannels->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los canales de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los canales de notificacion:' . $e->getMessage());
        }
    }

    public function find(NotificationChannelId $notificationChannelId): ?NotificationChannel
    {
        try {
            $model = $this->notificationChannelModel->withTrashed()
                ->with(['item'])->where('item_id', 22)
                ->whereKey($notificationChannelId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El canal de notificacion no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el canal de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el canal de notificacion:' . $e->getMessage());
        }
    }

    public function update(NotificationChannel $notificationChannel, NotificationChannelId $notificationChannelId): NotificationChannel
    {
        try {
            $model = tap($this->notificationChannelModel->where('item_id', 29)->whereKey($notificationChannelId->value())->firstOrFail())
                ->update($this->mapToModelData($notificationChannel))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El canal de notificacion no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el canal de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el canal de notificacion:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): NotificationChannel
    {
        return new NotificationChannel(
            new NotificationChannelId($model->id),
            new NotificationChannelItemId($model->item?->id ?? $model->item_id),
            new NotificationChannelName($model->name),
            new NotificationChannelDescription($model->description),
            new NotificationChannelSettings($model->settings),
            new NotificationChannelCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new NotificationChannelUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new NotificationChannelDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(NotificationChannel $notificationChannel): array
    {
        $array = [];

        if ($notificationChannel->itemId()->value()) {
            $array['item_id'] = $notificationChannel->itemId()->value();
        }

        if ($notificationChannel->name()->value()) {
            $array['name'] = $notificationChannel->name()->value();
        }

        if ($notificationChannel->description()->value()) {
            $array['description'] = $notificationChannel->description()->value();
        }

        if ($notificationChannel->settings()->value()) {
            $array['settings'] = $notificationChannel->settings()->value();
        }

        return $array;
    }
}
