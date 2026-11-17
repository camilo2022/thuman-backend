<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Enterprise\Contracts\EnterpriseContract;
use App\Models\Enterprise as EnterpriseModel;
use App\Models\File as FileModel;
use App\Domain\Enterprise\Enterprise;
use App\Domain\Enterprise\Logo;
use App\Domain\Enterprise\Values\EnterpriseCreatedAt;
use App\Domain\Enterprise\Values\EnterpriseDeletedAt;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Domain\Enterprise\Values\EnterpriseName;
use App\Domain\Enterprise\Values\EnterpriseDescription;
use App\Domain\Enterprise\Values\EnterpriseUpdatedAt;
use App\Domain\Enterprise\Values\LogoCreatedAt;
use App\Domain\Enterprise\Values\LogoDeletedAt;
use App\Domain\Enterprise\Values\LogoExtension;
use App\Domain\Enterprise\Values\LogoFileSubtypeId;
use App\Domain\Enterprise\Values\LogoFileTypeId;
use App\Domain\Enterprise\Values\LogoId;
use App\Domain\Enterprise\Values\LogoMetadata;
use App\Domain\Enterprise\Values\LogoMime;
use App\Domain\Enterprise\Values\LogoModelId;
use App\Domain\Enterprise\Values\LogoModelType;
use App\Domain\Enterprise\Values\LogoName;
use App\Domain\Enterprise\Values\LogoPath;
use App\Domain\Enterprise\Values\LogoSize;
use App\Domain\Enterprise\Values\LogoUpdatedAt;
use App\Http\Requests\Enterprise\EnterpriseAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EnterpriseRepository implements EnterpriseContract
{
    private $enterpriseModel;
    private $fileModel;

    public function __construct()
    {
        $this->enterpriseModel = new EnterpriseModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, EnterpriseAllRequest $request): LengthAwarePaginator
    {
        try {
            $enterprises = $this->enterpriseModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['entities', 'logo'])
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $enterprises->paginate($perPage ?? $enterprises->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las empresas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las empresas: ' . $e->getMessage());
        }
    }

    public function find(EnterpriseId $enterpriseId): ?Enterprise
    {
        try {
            $model = $this->enterpriseModel->withTrashed()
                ->with(['entities', 'logo'])
                ->findOrFail($enterpriseId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La empresa no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la empresa: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la empresa: ' . $e->getMessage());
        }
    }

    public function store(Enterprise $enterprise): Enterprise
    {
        try {
            $model = $this->enterpriseModel
                ->create($this->mapToModelData($enterprise));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la empresa: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la empresa: ' . $e->getMessage());
        }
    }

    public function update(Enterprise $enterprise, EnterpriseId $enterpriseId): Enterprise
    {
        try {
            $model = tap($this->enterpriseModel->findOrFail($enterpriseId->value()))
                ->update($this->mapToModelData($enterprise))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La empresa no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la empresa: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la empresa: ' . $e->getMessage());
        }
    }

    public function delete(EnterpriseId $enterpriseId): Enterprise
    {
        try {
            $model = tap($this->enterpriseModel->findOrFail($enterpriseId->value()), fn($enterprise) => $enterprise->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La empresa no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la empresa: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la empresa: ' . $e->getMessage());
        }
    }

    public function restore(EnterpriseId $enterpriseId): Enterprise
    {
        try {
            $model = tap($this->enterpriseModel->withTrashed()->findOrFail($enterpriseId->value()), fn($enterprise) => $enterprise->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El empresa no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la empresa: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la empresa: ' . $e->getMessage());
        }
    }

    public function logo(Logo $logo): Logo
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataLogo($logo, 'keys'), $this->mapToModelDataLogo($logo, 'data'));

            return $this->mapToEntityLogo($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El logo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar el logo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar el logo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Enterprise
    {
        return new Enterprise(
            new EnterpriseId($model->id),
            new EnterpriseName($model->name),
            new EnterpriseDescription($model->description),
            new EnterpriseCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new EnterpriseUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new EnterpriseDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            $model->logo ? $this->mapToEntityLogo($model->logo) : null
        );
    }

    private function mapToEntityLogo($model): Logo
    {
        return new Logo(
            new LogoId($model->id),
            new LogoModelId($model->model?->id ?? $model->model_id),
            new LogoModelType($model->model_type),
            new LogoFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new LogoFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new LogoName($model->name),
            new LogoPath($model->path),
            new LogoMime($model->mime),
            new LogoExtension($model->extension),
            new LogoSize($model->size),
            new LogoMetadata($model->metadata),
            new LogoCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new LogoUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new LogoDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Enterprise $enterprise): array
    {
        $array = [];

        if ($enterprise->name()->value()) {
            $array['name'] = $enterprise->name()->value();
        }

        if ($enterprise->description()->value()) {
            $array['description'] = $enterprise->description()->value();
        }

        return $array;
    }

    private function mapToModelDataLogo(Logo $logo, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($logo->modelId()->value()) {
            $array['keys']['model_id'] = $logo->modelId()->value();
        }

        if ($logo->modelType()->value()) {
            $array['keys']['model_type'] = $logo->modelType()->value();
        }

        if ($logo->fileTypeId()->value()) {
            $array['data']['file_type_id'] = $logo->fileTypeId()->value();
        }

        if ($logo->fileSubtypeId()->value()) {
            $array['data']['file_subtype_id'] = $logo->fileSubtypeId()->value();
        }

        if ($logo->name()->value()) {
            $array['data']['name'] = $logo->name()->value();
        }

        if ($logo->path()->value()) {
            $array['data']['path'] = $logo->path()->value();
        }

        if ($logo->mime()->value()) {
            $array['data']['mime'] = $logo->mime()->value();
        }

        if ($logo->extension()->value()) {
            $array['data']['extension'] = $logo->extension()->value();
        }

        if ($logo->size()->value()) {
            $array['data']['size'] = $logo->size()->value();
        }

        if ($logo->metadata()->value()) {
            $array['data']['metadata'] = $logo->metadata()->value();
        }

        return $array[$index];
    }
}
