<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\ResignationType\Contracts\ResignationTypeContract;
use App\Models\ResignationType as ResignationTypeModel;
use App\Domain\ResignationType\ResignationType;
use App\Domain\ResignationType\Values\ResignationTypeCreatedAt;
use App\Domain\ResignationType\Values\ResignationTypeDeletedAt;
use App\Domain\ResignationType\Values\ResignationTypeId;
use App\Domain\ResignationType\Values\ResignationTypeName;
use App\Domain\ResignationType\Values\ResignationTypeDescription;
use App\Domain\ResignationType\Values\ResignationTypeItemId;
use App\Domain\ResignationType\Values\ResignationTypeRelationships;
use App\Domain\ResignationType\Values\ResignationTypeSettings;
use App\Domain\ResignationType\Values\ResignationTypeUpdatedAt;
use App\Http\Requests\ResignationType\ResignationTypeAllRequest;
use App\Models\Resignation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ResignationTypeRepository implements ResignationTypeContract
{
    private $resignationTypeModel;

    public function __construct()
    {
        $this->resignationTypeModel = new ResignationTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, ResignationTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $resignationTypes = $this->resignationTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 46)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $resignationTypes->paginate($perPage ?? $resignationTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de renuncias: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de renuncias: ' . $e->getMessage());
        }
    }

    public function find(ResignationTypeId $resignationTypeId): ?ResignationType
    {
        try {
            $model = $this->resignationTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 46)
                ->whereKey($resignationTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de renuncia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de renuncia: ' . $e->getMessage());
        }
    }

    public function store(ResignationType $resignationType): ResignationType
    {
        try {
            $model = $this->resignationTypeModel
                ->create($this->mapToModelData($resignationType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de renuncia: ' . $e->getMessage());
        }
    }

    public function update(ResignationType $resignationType, ResignationTypeId $resignationTypeId): ResignationType
    {
        try {
            $model = tap($this->resignationTypeModel->where('item_id', 46)->whereKey($resignationTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($resignationType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de renuncia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de renuncia: ' . $e->getMessage());
        }
    }

    public function delete(ResignationTypeId $resignationTypeId): ResignationType
    {
        try {
            $model = $this->resignationTypeModel->where('item_id', 46)->whereKey($resignationTypeId->value())->firstOrFail();

            $isUsed = Resignation::where('resignation_type_id', $resignationTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de renuncia no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de renuncia: ' . $e->getMessage());
        }
    }

    public function restore(ResignationTypeId $resignationTypeId): ResignationType
    {
        try {
            $model = tap($this->resignationTypeModel->withTrashed()->where('item_id', 46)->whereKey($resignationTypeId->value())->firstOrFail(), fn($resignationType) => $resignationType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de renuncia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de renuncia: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): ResignationType
    {
        return new ResignationType(
            new ResignationTypeId($model->id),
            new ResignationTypeItemId($model->item?->id ?? $model->item_id),
            new ResignationTypeName($model->name),
            new ResignationTypeDescription($model->description),
            new ResignationTypeSettings($model->settings),
            new ResignationTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ResignationTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ResignationTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new ResignationTypeRelationships($model->item)
        );
    }

    private function mapToModelData(ResignationType $resignationType): array
    {
        $array = [];

        if ($resignationType->itemId()->value()) {
            $array['item_id'] = $resignationType->itemId()->value();
        }

        if ($resignationType->name()->value()) {
            $array['name'] = $resignationType->name()->value();
        }

        if ($resignationType->description()->value()) {
            $array['description'] = $resignationType->description()->value();
        }

        if ($resignationType->settings()->fileRequired() !== null) {
            $array['settings']['file_required'] = $resignationType->settings()->fileRequired();
        }

        if ($resignationType->settings()->message()) {
            $array['settings']['message'] = $resignationType->settings()->message();
        }

        if ($resignationType->settings()->validations()) {
            $array['settings']['validations'] = $resignationType->settings()->validations(false);
        }

        return $array;
    }
}
