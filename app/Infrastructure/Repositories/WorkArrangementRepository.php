<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\WorkArrangement\Contracts\WorkArrangementContract;
use App\Models\WorkArrangement as WorkArrangementModel;
use App\Domain\WorkArrangement\WorkArrangement;
use App\Domain\WorkArrangement\Values\WorkArrangementCreatedAt;
use App\Domain\WorkArrangement\Values\WorkArrangementDeletedAt;
use App\Domain\WorkArrangement\Values\WorkArrangementId;
use App\Domain\WorkArrangement\Values\WorkArrangementName;
use App\Domain\WorkArrangement\Values\WorkArrangementDescription;
use App\Domain\WorkArrangement\Values\WorkArrangementItemId;
use App\Domain\WorkArrangement\Values\WorkArrangementSettings;
use App\Domain\WorkArrangement\Values\WorkArrangementUpdatedAt;
use App\Http\Requests\WorkArrangement\WorkArrangementAllRequest;
use App\Models\Vacant;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class WorkArrangementRepository implements WorkArrangementContract
{
    private $workArrangementModel;

    public function __construct()
    {
        $this->workArrangementModel = new WorkArrangementModel;
    }

    public function all(string|null $search = null, int|null $perPage, WorkArrangementAllRequest $request): LengthAwarePaginator
    {
        try {
            $workArrangements = $this->workArrangementModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 3)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $workArrangements->paginate($perPage ?? $workArrangements->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las modalidades de trabajo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las modalidades de trabajo: ' . $e->getMessage());
        }
    }

    public function find(WorkArrangementId $workArrangementId): ?WorkArrangement
    {
        try {
            $model = $this->workArrangementModel->withTrashed()
                ->with(['item'])->where('item_id', 3)
                ->whereKey($workArrangementId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La modalidad de trabajo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la modalidad de trabajo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la modalidad de trabajo: ' . $e->getMessage());
        }
    }

    public function store(WorkArrangement $workArrangement): WorkArrangement
    {
        try {
            $model = $this->workArrangementModel
                ->create($this->mapToModelData($workArrangement));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la modalidad de trabajo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la modalidad de trabajo: ' . $e->getMessage());
        }
    }

    public function update(WorkArrangement $workArrangement, WorkArrangementId $workArrangementId): WorkArrangement
    {
        try {
            $model = tap($this->workArrangementModel->where('item_id', 3)->whereKey($workArrangementId->value())->firstOrFail())
                ->update($this->mapToModelData($workArrangement))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La modalidad de trabajo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la modalidad de trabajo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la modalidad de trabajo: ' . $e->getMessage());
        }
    }

    public function delete(WorkArrangementId $workArrangementId): WorkArrangement
    {
        try {

            $model = $this->workArrangementModel
                ->where('item_id', 3)
                ->whereKey($workArrangementId->value())
                ->firstOrFail();

            $isUsed = Vacant::where('work_arrangement_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La modalidad de trabajo no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la modalidad de trabajo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la modalidad de trabajo: ' . $e->getMessage());
        }
    }

    public function restore(WorkArrangementId $workArrangementId): WorkArrangement
    {
        try {
            $model = tap($this->workArrangementModel->withTrashed()->where('item_id', 3)->whereKey($workArrangementId->value())->firstOrFail(), fn($workArrangement) => $workArrangement->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La modalidad de trabajo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la modalidad de trabajo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la modalidad de trabajo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): WorkArrangement
    {
        return new WorkArrangement(
            new WorkArrangementId($model->id),
            new WorkArrangementItemId($model->item_id),
            new WorkArrangementName($model->name),
            new WorkArrangementDescription($model->description),
            new WorkArrangementSettings($model->settings),
            new WorkArrangementCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new WorkArrangementUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new WorkArrangementDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(WorkArrangement $workArrangement): array
    {
        $array = [];

        if ($workArrangement->itemId()->value()) {
            $array['item_id'] = $workArrangement->itemId()->value();
        }

        if ($workArrangement->name()->value()) {
            $array['name'] = $workArrangement->name()->value();
        }

        if ($workArrangement->description()->value()) {
            $array['description'] = $workArrangement->description()->value();
        }

        if ($workArrangement->settings()->value()) {
            $array['settings'] = $workArrangement->settings()->value();
        }

        return $array;
    }
}
