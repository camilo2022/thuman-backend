<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\MaritalStatus\Contracts\MaritalStatusContract;
use App\Models\MaritalStatus as MaritalStatusModel;
use App\Domain\MaritalStatus\MaritalStatus;
use App\Domain\MaritalStatus\Values\MaritalStatusCreatedAt;
use App\Domain\MaritalStatus\Values\MaritalStatusDeletedAt;
use App\Domain\MaritalStatus\Values\MaritalStatusId;
use App\Domain\MaritalStatus\Values\MaritalStatusName;
use App\Domain\MaritalStatus\Values\MaritalStatusDescription;
use App\Domain\MaritalStatus\Values\MaritalStatusItemId;
use App\Domain\MaritalStatus\Values\MaritalStatusSettings;
use App\Domain\MaritalStatus\Values\MaritalStatusUpdatedAt;
use App\Http\Requests\MaritalStatus\MaritalStatusAllRequest;
use App\Models\Person;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class MaritalStatusRepository implements MaritalStatusContract
{
    private $maritalStatusModel;

    public function __construct()
    {
        $this->maritalStatusModel = new MaritalStatusModel;
    }

    public function all(string|null $search = null, int|null $perPage, MaritalStatusAllRequest $request): LengthAwarePaginator
    {
        try {
            $maritalStatuses = $this->maritalStatusModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 12)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $maritalStatuses->paginate($perPage ?? $maritalStatuses->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los estados civiles: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los estados civiles: ' . $e->getMessage());
        }
    }

    public function find(MaritalStatusId $maritalStatusId): ?MaritalStatus
    {
        try {
            $model = $this->maritalStatusModel->withTrashed()
                ->with(['item'])->where('item_id', 12)
                ->whereKey($maritalStatusId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estado civil no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el estado civil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el estado civil: ' . $e->getMessage());
        }
    }

    public function store(MaritalStatus $maritalStatus): MaritalStatus
    {
        try {
            $model = $this->maritalStatusModel
                ->create($this->mapToModelData($maritalStatus));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el estado civil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el estado civil: ' . $e->getMessage());
        }
    }

    public function update(MaritalStatus $maritalStatus, MaritalStatusId $maritalStatusId): MaritalStatus
    {
        try {
            $model = tap($this->maritalStatusModel->where('item_id', 12)->whereKey($maritalStatusId->value())->firstOrFail())
                ->update($this->mapToModelData($maritalStatus))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estado civil no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el estado civil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el estado civil: ' . $e->getMessage());
        }
    }

    public function delete(MaritalStatusId $maritalStatusId): MaritalStatus
    {
        try {

            $model = $this->maritalStatusModel
                ->where('item_id', 12)
                ->whereKey($maritalStatusId->value())
                ->firstOrFail();

            $isUsed = Person::where('marital_status_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estado civil no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el estado civil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el estado civil: ' . $e->getMessage());
        }
    }

    public function restore(MaritalStatusId $maritalStatusId): MaritalStatus
    {
        try {
            $model = tap($this->maritalStatusModel->withTrashed()->where('item_id', 12)->whereKey($maritalStatusId->value())->firstOrFail(), fn($maritalStatus) => $maritalStatus->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estado civil no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el estado civil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el estado civil: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): MaritalStatus
    {
        return new MaritalStatus(
            new MaritalStatusId($model->id),
            new MaritalStatusItemId($model->item?->id ?? $model->item_id),
            new MaritalStatusName($model->name),
            new MaritalStatusDescription($model->description),
            new MaritalStatusSettings($model->settings),
            new MaritalStatusCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new MaritalStatusUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new MaritalStatusDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(MaritalStatus $maritalStatus): array
    {
        $array = [];

        if ($maritalStatus->itemId()->value()) {
            $array['item_id'] = $maritalStatus->itemId()->value();
        }

        if ($maritalStatus->name()->value()) {
            $array['name'] = $maritalStatus->name()->value();
        }

        if ($maritalStatus->description()->value()) {
            $array['description'] = $maritalStatus->description()->value();
        }

        if ($maritalStatus->settings()->value()) {
            $array['settings'] = $maritalStatus->settings()->value();
        }

        return $array;
    }
}
