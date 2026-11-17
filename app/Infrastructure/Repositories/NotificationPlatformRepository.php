<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\NotificationPlatform\Contracts\NotificationPlatformContract;
use App\Models\NotificationPlatform as NotificationPlatformModel;
use App\Domain\NotificationPlatform\NotificationPlatform;
use App\Domain\NotificationPlatform\Values\NotificationPlatformCreatedAt;
use App\Domain\NotificationPlatform\Values\NotificationPlatformDeletedAt;
use App\Domain\NotificationPlatform\Values\NotificationPlatformId;
use App\Domain\NotificationPlatform\Values\NotificationPlatformName;
use App\Domain\NotificationPlatform\Values\NotificationPlatformDescription;
use App\Domain\NotificationPlatform\Values\NotificationPlatformItemId;
use App\Domain\NotificationPlatform\Values\NotificationPlatformSettings;
use App\Domain\NotificationPlatform\Values\NotificationPlatformUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class NotificationPlatformRepository implements NotificationPlatformContract
{
    private $notificationPlatformModel;

    public function __construct()
    {
        $this->notificationPlatformModel = new NotificationPlatformModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $notificationPlatforms = $this->notificationPlatformModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 22)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $notificationPlatforms->paginate($perPage ?? $notificationPlatforms->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las plataformas de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las plataformas de notificacion:' . $e->getMessage());
        }
    }

    public function find(NotificationPlatformId $notificationPlatformId): ?NotificationPlatform
    {
        try {
            $model = $this->notificationPlatformModel->withTrashed()
                ->with(['item'])->where('item_id', 22)
                ->whereKey($notificationPlatformId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La plataforma de notificacion no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la plataforma de notificacion:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la plataforma de notificacion:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): NotificationPlatform
    {
        return new NotificationPlatform(
            new NotificationPlatformId($model->id),
            new NotificationPlatformItemId($model->item?->id ?? $model->item_id),
            new NotificationPlatformName($model->name),
            new NotificationPlatformDescription($model->description),
            new NotificationPlatformSettings($model->settings),
            new NotificationPlatformCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new NotificationPlatformUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new NotificationPlatformDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
