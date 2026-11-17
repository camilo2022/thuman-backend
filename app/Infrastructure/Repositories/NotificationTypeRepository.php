<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\NotificationType\Contracts\NotificationTypeContract;
use App\Models\NotificationType as NotificationTypeModel;
use App\Domain\NotificationType\NotificationType;
use App\Domain\NotificationType\Values\NotificationTypeCreatedAt;
use App\Domain\NotificationType\Values\NotificationTypeDeletedAt;
use App\Domain\NotificationType\Values\NotificationTypeId;
use App\Domain\NotificationType\Values\NotificationTypeName;
use App\Domain\NotificationType\Values\NotificationTypeDescription;
use App\Domain\NotificationType\Values\NotificationTypeItemId;
use App\Domain\NotificationType\Values\NotificationTypeSettings;
use App\Domain\NotificationType\Values\NotificationTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class NotificationTypeRepository implements NotificationTypeContract
{
    private $notificationTypeModel;

    public function __construct()
    {
        $this->notificationTypeModel = new NotificationTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $notificationTypes = $this->notificationTypeModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 39)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $notificationTypes->paginate($perPage ?? $notificationTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de notificacion:' . $e->getMessage());
        }
    }

    public function find(NotificationTypeId $notificationTypeId): ?NotificationType
    {
        try {
            $model = $this->notificationTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 39)
                ->whereKey($notificationTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de notificacion no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de notificacion:' . $e->getMessage());
        }
    }

    public function update(NotificationType $notificationType, NotificationTypeId $notificationTypeId): NotificationType
    {
        try {
            $model = tap($this->notificationTypeModel->where('item_id', 39)->whereKey($notificationTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($notificationType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de notificacion no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de notificacion:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): NotificationType
    {
        return new NotificationType(
            new NotificationTypeId($model->id),
            new NotificationTypeItemId($model->item?->id ?? $model->item_id),
            new NotificationTypeName($model->name),
            new NotificationTypeDescription($model->description),
            new NotificationTypeSettings($model->settings),
            new NotificationTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new NotificationTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new NotificationTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(NotificationType $notificationType): array
    {
        $array = [];

        if ($notificationType->itemId()->value()) {
            $array['item_id'] = $notificationType->itemId()->value();
        }

        if ($notificationType->name()->value()) {
            $array['name'] = $notificationType->name()->value();
        }

        if ($notificationType->description()->value()) {
            $array['description'] = $notificationType->description()->value();
        }

        if ($notificationType->settings()->value()) {
            $array['settings'] = $notificationType->settings()->value();
        }

        return $array;
    }
}
