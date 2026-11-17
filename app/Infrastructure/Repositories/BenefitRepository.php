<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Benefit\Contracts\BenefitContract;
use App\Models\Benefit as BenefitModel;
use App\Models\File as FileModel;
use App\Domain\Benefit\Benefit;
use App\Domain\Benefit\Icon;
use App\Domain\Benefit\Values\BenefitCreatedAt;
use App\Domain\Benefit\Values\BenefitDeletedAt;
use App\Domain\Benefit\Values\BenefitId;
use App\Domain\Benefit\Values\BenefitName;
use App\Domain\Benefit\Values\BenefitDescription;
use App\Domain\Benefit\Values\BenefitItemId;
use App\Domain\Benefit\Values\BenefitSettings;
use App\Domain\Benefit\Values\BenefitUpdatedAt;
use App\Domain\Benefit\Values\IconCreatedAt;
use App\Domain\Benefit\Values\IconDeletedAt;
use App\Domain\Benefit\Values\IconExtension;
use App\Domain\Benefit\Values\IconFileSubtypeId;
use App\Domain\Benefit\Values\IconFileTypeId;
use App\Domain\Benefit\Values\IconId;
use App\Domain\Benefit\Values\IconMetadata;
use App\Domain\Benefit\Values\IconMime;
use App\Domain\Benefit\Values\IconModelId;
use App\Domain\Benefit\Values\IconModelType;
use App\Domain\Benefit\Values\IconName;
use App\Domain\Benefit\Values\IconPath;
use App\Domain\Benefit\Values\IconSize;
use App\Domain\Benefit\Values\IconUpdatedAt;
use App\Http\Requests\Benefit\BenefitAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class BenefitRepository implements BenefitContract
{
    private $benefitModel;
    private $fileModel;

    public function __construct()
    {
        $this->benefitModel = new BenefitModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, BenefitAllRequest $request): LengthAwarePaginator
    {
        try {
            $benefits = $this->benefitModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'icon'])
                ->where('item_id', 19)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $benefits->paginate($perPage ?? $benefits->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los beneficios: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los beneficios: ' . $e->getMessage());
        }
    }

    public function find(BenefitId $benefitId): ?Benefit
    {
        try {
            $model = $this->benefitModel->withTrashed()
                ->with(['item', 'icon'])->where('item_id', 19)
                ->findOrFail($benefitId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El beneficio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el beneficio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el beneficio: ' . $e->getMessage());
        }
    }

    public function store(Benefit $benefit): Benefit
    {
        try {
            $model = $this->benefitModel
                ->create($this->mapToModelData($benefit));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el beneficio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el beneficio: ' . $e->getMessage());
        }
    }

    public function update(Benefit $benefit, BenefitId $benefitId): Benefit
    {
        try {
            $model = tap($this->benefitModel->where('item_id', 19)->whereKey($benefitId->value())->firstOrFail())
                ->update($this->mapToModelData($benefit))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El beneficio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el beneficio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el beneficio: ' . $e->getMessage());
        }
    }

    public function delete(BenefitId $benefitId): Benefit
    {
        try {
            $model = tap($this->benefitModel->where('item_id', 19)->whereKey($benefitId->value())->firstOrFail(), fn($benefit) => $benefit->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El beneficio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el beneficio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el beneficio: ' . $e->getMessage());
        }
    }

    public function restore(BenefitId $benefitId): Benefit
    {
        try {
            $model = tap($this->benefitModel->withTrashed()->where('item_id', 19)->whereKey($benefitId->value())->firstOrFail(), fn($benefit) => $benefit->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El beneficio no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el beneficio: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el beneficio: ' . $e->getMessage());
        }
    }

    public function icon(Icon $icon): Icon
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataIcon($icon, 'keys'), $this->mapToModelDataIcon($icon, 'data'));

            return $this->mapToEntityIcon($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El icono no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar el icono: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar el icono: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Benefit
    {
        return new Benefit(
            new BenefitId($model->id),
            new BenefitItemId($model->item_id),
            new BenefitName($model->name),
            new BenefitDescription($model->description),
            new BenefitSettings($model->settings),
            new BenefitCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new BenefitUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new BenefitDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            $model->icon ? $this->mapToEntityIcon($model->icon) : null
        );
    }

    private function mapToEntityIcon($model): Icon
    {
        return new Icon(
            new IconId($model->id),
            new IconModelId($model->model?->id ?? $model->model_id),
            new IconModelType($model->model_type),
            new IconFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new IconFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new IconName($model->name),
            new IconPath($model->path),
            new IconMime($model->mime),
            new IconExtension($model->extension),
            new IconSize($model->size),
            new IconMetadata($model->metadata),
            new IconCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new IconUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new IconDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Benefit $benefit): array
    {
        $array = [];

        if ($benefit->itemId()->value()) {
            $array['item_id'] = $benefit->itemId()->value();
        }

        if ($benefit->name()->value()) {
            $array['name'] = $benefit->name()->value();
        }

        if ($benefit->description()->value()) {
            $array['description'] = $benefit->description()->value();
        }

        if ($benefit->settings()->value()) {
            $array['settings'] = $benefit->settings()->value();
        }

        return $array;
    }

    private function mapToModelDataIcon(Icon $icon, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($icon->modelId()->value()) {
            $array['keys']['model_id'] = $icon->modelId()->value();
        }

        if ($icon->modelType()->value()) {
            $array['keys']['model_type'] = $icon->modelType()->value();
        }

        if ($icon->fileTypeId()->value()) {
            $array['data']['file_type_id'] = $icon->fileTypeId()->value();
        }

        if ($icon->fileSubtypeId()->value()) {
            $array['data']['file_subtype_id'] = $icon->fileSubtypeId()->value();
        }

        if ($icon->name()->value()) {
            $array['data']['name'] = $icon->name()->value();
        }

        if ($icon->path()->value()) {
            $array['data']['path'] = $icon->path()->value();
        }

        if ($icon->mime()->value()) {
            $array['data']['mime'] = $icon->mime()->value();
        }

        if ($icon->extension()->value()) {
            $array['data']['extension'] = $icon->extension()->value();
        }

        if ($icon->size()->value()) {
            $array['data']['size'] = $icon->size()->value();
        }

        if ($icon->metadata()->value()) {
            $array['data']['metadata'] = $icon->metadata()->value();
        }

        return $array[$index];
    }
}
