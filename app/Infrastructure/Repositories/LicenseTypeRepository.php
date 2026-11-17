<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\LicenseType\Contracts\LicenseTypeContract;
use App\Models\LicenseType as LicenseTypeModel;
use App\Domain\LicenseType\LicenseType;
use App\Domain\LicenseType\Values\LicenseTypeCreatedAt;
use App\Domain\LicenseType\Values\LicenseTypeDeletedAt;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Domain\LicenseType\Values\LicenseTypeName;
use App\Domain\LicenseType\Values\LicenseTypeDescription;
use App\Domain\LicenseType\Values\LicenseTypeItemId;
use App\Domain\LicenseType\Values\LicenseTypeRelationships;
use App\Domain\LicenseType\Values\LicenseTypeSettings;
use App\Domain\LicenseType\Values\LicenseTypeUpdatedAt;
use App\Http\Requests\LicenseType\LicenseTypeAllRequest;
use App\Models\License;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LicenseTypeRepository implements LicenseTypeContract
{
    private $licenseTypeModel;

    public function __construct()
    {
        $this->licenseTypeModel = new LicenseTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, LicenseTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $licenseTypes = $this->licenseTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 44)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $licenseTypes->paginate($perPage ?? $licenseTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de licencias: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de licencias: ' . $e->getMessage());
        }
    }

    public function find(LicenseTypeId $licenseTypeId): ?LicenseType
    {
        try {
            $model = $this->licenseTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 44)
                ->whereKey($licenseTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de licencia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de licencia: ' . $e->getMessage());
        }
    }

    public function store(LicenseType $licenseType): LicenseType
    {
        try {
            $model = $this->licenseTypeModel
                ->create($this->mapToModelData($licenseType));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de licencia: ' . $e->getMessage());
        }
    }

    public function update(LicenseType $licenseType, LicenseTypeId $licenseTypeId): LicenseType
    {
        try {
            $model = tap($this->licenseTypeModel->where('item_id', 44)->whereKey($licenseTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($licenseType))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de licencia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de licencia: ' . $e->getMessage());
        }
    }

    public function delete(LicenseTypeId $licenseTypeId): LicenseType
    {
        try {
            $model = $this->licenseTypeModel->where('item_id', 44)->whereKey($licenseTypeId->value())->firstOrFail();

            $isUsed = License::where('license_type_id', $licenseTypeId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de licencia no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de licencia: ' . $e->getMessage());
        }
    }

    public function restore(LicenseTypeId $licenseTypeId): LicenseType
    {
        try {
            $model = tap($this->licenseTypeModel->withTrashed()->where('item_id', 44)->whereKey($licenseTypeId->value())->firstOrFail(), fn($licenseType) => $licenseType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de licencia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de licencia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de licencia: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): LicenseType
    {
        return new LicenseType(
            new LicenseTypeId($model->id),
            new LicenseTypeItemId($model->item?->id ?? $model->item_id),
            new LicenseTypeName($model->name),
            new LicenseTypeDescription($model->description),
            new LicenseTypeSettings($model->settings),
            new LicenseTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new LicenseTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new LicenseTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new LicenseTypeRelationships($model->item)
        );
    }

    private function mapToModelData(LicenseType $licenseType): array
    {
        $array = [];

        if ($licenseType->itemId()->value()) {
            $array['item_id'] = $licenseType->itemId()->value();
        }

        if ($licenseType->name()->value()) {
            $array['name'] = $licenseType->name()->value();
        }

        if ($licenseType->description()->value()) {
            $array['description'] = $licenseType->description()->value();
        }

        if ($licenseType->settings()->fileRequired() !== null) {
            $array['settings']['file_required'] = $licenseType->settings()->fileRequired();
        }

        if ($licenseType->settings()->message()) {
            $array['settings']['message'] = $licenseType->settings()->message();
        }

        if ($licenseType->settings()->validations()) {
            $array['settings']['validations'] = $licenseType->settings()->validations(false);
        }

        return $array;
    }
}
