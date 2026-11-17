<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Sex\Contracts\SexContract;
use App\Models\Sex as SexModel;
use App\Domain\Sex\Sex;
use App\Domain\Sex\Values\SexCreatedAt;
use App\Domain\Sex\Values\SexDeletedAt;
use App\Domain\Sex\Values\SexId;
use App\Domain\Sex\Values\SexName;
use App\Domain\Sex\Values\SexDescription;
use App\Domain\Sex\Values\SexItemId;
use App\Domain\Sex\Values\SexSettings;
use App\Domain\Sex\Values\SexUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SexRepository implements SexContract
{
    private $sexModel;

    public function __construct()
    {
        $this->sexModel = new SexModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $sexes = $this->sexModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 9)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $sexes->paginate($perPage ?? $sexes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los sexos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los sexos: ' . $e->getMessage());
        }
    }

    public function find(SexId $sexId): ?Sex
    {
        try {
            $model = $this->sexModel->withTrashed()
                ->with(['item'])->where('item_id', 9)
                ->whereKey($sexId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El sexo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el sexo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el sexo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Sex
    {
        return new Sex(
            new SexId($model->id),
            new SexItemId($model->item?->id ?? $model->item_id),
            new SexName($model->name),
            new SexDescription($model->description),
            new SexSettings($model->settings),
            new SexCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SexUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SexDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
