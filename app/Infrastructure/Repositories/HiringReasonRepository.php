<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\HiringReason\Contracts\HiringReasonContract;
use App\Models\HiringReason as HiringReasonModel;
use App\Domain\HiringReason\HiringReason;
use App\Domain\HiringReason\Values\HiringReasonCreatedAt;
use App\Domain\HiringReason\Values\HiringReasonDeletedAt;
use App\Domain\HiringReason\Values\HiringReasonId;
use App\Domain\HiringReason\Values\HiringReasonName;
use App\Domain\HiringReason\Values\HiringReasonDescription;
use App\Domain\HiringReason\Values\HiringReasonItemId;
use App\Domain\HiringReason\Values\HiringReasonSettings;
use App\Domain\HiringReason\Values\HiringReasonUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class HiringReasonRepository implements HiringReasonContract
{
    private $hiringReasonModel;

    public function __construct()
    {
        $this->hiringReasonModel = new HiringReasonModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $hiringReasons = $this->hiringReasonModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 33)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $hiringReasons->paginate($perPage ?? $hiringReasons->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los motivos solicitud de personal:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los motivos solicitud de personal:' . $e->getMessage());
        }
    }

    public function find(HiringReasonId $hiringReasonId): ?HiringReason
    {
        try {
            $model = $this->hiringReasonModel->withTrashed()
                ->with(['item'])->where('item_id', 33)
                ->whereKey($hiringReasonId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El motivo solicitud de personal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el motivo solicitud de personal:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el motivo solicitud de personal:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): HiringReason
    {
        return new HiringReason(
            new HiringReasonId($model->id),
            new HiringReasonItemId($model->item?->id ?? $model->item_id),
            new HiringReasonName($model->name),
            new HiringReasonDescription($model->description),
            new HiringReasonSettings($model->settings),
            new HiringReasonCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new HiringReasonUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new HiringReasonDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
