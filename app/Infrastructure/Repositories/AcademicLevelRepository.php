<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\AcademicLevel\Contracts\AcademicLevelContract;
use App\Models\AcademicLevel as AcademicLevelModel;
use App\Domain\AcademicLevel\AcademicLevel;
use App\Domain\AcademicLevel\Values\AcademicLevelCreatedAt;
use App\Domain\AcademicLevel\Values\AcademicLevelDeletedAt;
use App\Domain\AcademicLevel\Values\AcademicLevelId;
use App\Domain\AcademicLevel\Values\AcademicLevelName;
use App\Domain\AcademicLevel\Values\AcademicLevelDescription;
use App\Domain\AcademicLevel\Values\AcademicLevelItemId;
use App\Domain\AcademicLevel\Values\AcademicLevelSettings;
use App\Domain\AcademicLevel\Values\AcademicLevelUpdatedAt;
use App\Http\Requests\AcademicLevel\AcademicLevelAllRequest;
use App\Models\AcademicQualification;
use App\Models\AcademicRequirement;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AcademicLevelRepository implements AcademicLevelContract
{
    private $academicLevelModel;

    public function __construct()
    {
        $this->academicLevelModel = new AcademicLevelModel;
    }

    public function all(string|null $search = null, int|null $perPage, AcademicLevelAllRequest $request): LengthAwarePaginator
    {
        try {
            $academicLevels = $this->academicLevelModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 8)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $academicLevels->paginate($perPage ?? $academicLevels->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los niveles academicos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los niveles academicos: ' . $e->getMessage());
        }
    }

    public function find(AcademicLevelId $academicLevelId): ?AcademicLevel
    {
        try {
            $model = $this->academicLevelModel->withTrashed()
                ->with(['item'])->where('item_id', 8)
                ->whereKey($academicLevelId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El nivel academico no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el nivel academico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el nivel academico: ' . $e->getMessage());
        }
    }

    public function store(AcademicLevel $academicLevel): AcademicLevel
    {
        try {
            $model = $this->academicLevelModel
                ->create($this->mapToModelData($academicLevel));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el nivel academico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el nivel academico: ' . $e->getMessage());
        }
    }

    public function update(AcademicLevel $academicLevel, AcademicLevelId $academicLevelId): AcademicLevel
    {
        try {
            $model = tap($this->academicLevelModel->where('item_id', 8)->whereKey($academicLevelId->value())->firstOrFail())
                ->update($this->mapToModelData($academicLevel))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El nivel academico no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el nivel academico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el nivel academico: ' . $e->getMessage());
        }
    }

    public function delete(AcademicLevelId $academicLevelId): AcademicLevel
    {
        try {

            $model = $this->academicLevelModel->where('item_id', 8)->whereKey($academicLevelId->value())->firstOrFail();

            $isUsed = AcademicRequirement::where('academic_level_id', $academicLevelId->value())->exists() ||
                AcademicQualification::where('academic_level_id', $academicLevelId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El nivel academico no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el nivel academico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el nivel academico: ' . $e->getMessage());
        }
    }

    public function restore(AcademicLevelId $academicLevelId): AcademicLevel
    {
        try {
            $model = tap($this->academicLevelModel->withTrashed()->where('item_id', 8)->whereKey($academicLevelId->value())->firstOrFail(), fn($academicLevel) => $academicLevel->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El nivel academico no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el nivel academico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el nivel academico: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): AcademicLevel
    {
        return new AcademicLevel(
            new AcademicLevelId($model->id),
            new AcademicLevelItemId($model->item?->id ?? $model->item_id),
            new AcademicLevelName($model->name),
            new AcademicLevelDescription($model->description),
            new AcademicLevelSettings($model->settings),
            new AcademicLevelCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AcademicLevelUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AcademicLevelDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(AcademicLevel $academicLevel): array
    {
        $array = [];

        if ($academicLevel->itemId()->value()) {
            $array['item_id'] = $academicLevel->itemId()->value();
        }

        if ($academicLevel->name()->value()) {
            $array['name'] = $academicLevel->name()->value();
        }

        if ($academicLevel->description()->value()) {
            $array['description'] = $academicLevel->description()->value();
        }

        if ($academicLevel->settings()->value()) {
            $array['settings'] = $academicLevel->settings()->value();
        }

        return $array;
    }
}
