<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Position\RequiredExperience\Contracts\RequiredExperienceContract;
use App\Models\RequiredExperience as RequiredExperienceModel;
use App\Domain\Position\RequiredExperience\RequiredExperience;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceCreatedAt;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceDescription;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceDurationMonths;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceName;
use App\Domain\Position\RequiredExperience\Values\RequiredExperiencePositionId;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceUpdatedAt;
use App\Domain\Position\Values\PositionId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RequiredExperienceRepository implements RequiredExperienceContract
{
    private $requiredExperienceModel;

    public function __construct()
    {
        $this->requiredExperienceModel = new RequiredExperienceModel;
    }

    public function all(string|null $search = null, int|null $perPage, PositionId $positionId): LengthAwarePaginator
    {
        try {
            $requiredExperiences = $this->requiredExperienceModel
                ->with(['position'])
                ->where('position_id', $positionId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $requiredExperiences->paginate($perPage ?? $requiredExperiences->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las experiencia requerida: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las experiencia requerida: ' . $e->getMessage());
        }
    }

    public function find(RequiredExperienceId $requiredExperienceId): ?RequiredExperience
    {
        try {
            $model = $this->requiredExperienceModel
                ->with(['position'])
                ->findOrFail($requiredExperienceId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La experiencia requerida no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la experiencia requerida: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la experiencia requerida: ' . $e->getMessage());
        }
    }

    public function store(RequiredExperience $requiredExperience): RequiredExperience
    {
        try {
            $model = $this->requiredExperienceModel
                ->create($this->mapToModelData($requiredExperience));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la experiencia requerida: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la experiencia requerida: ' . $e->getMessage());
        }
    }

    public function update(RequiredExperience $requiredExperience, RequiredExperienceId $requiredExperienceId): RequiredExperience
    {
        try {
            $model = tap($this->requiredExperienceModel->findOrFail($requiredExperienceId->value()))
                ->update($this->mapToModelData($requiredExperience))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La experiencia requerida no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la experiencia requerida: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la experiencia requerida: ' . $e->getMessage());
        }
    }

    public function delete(RequiredExperienceId $requiredExperienceId): void
    {
        try {
            $this->requiredExperienceModel->findOrFail($requiredExperienceId->value())
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La experiencia requerida no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la experiencia requerida: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la experiencia requerida: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): RequiredExperience
    {
        return new RequiredExperience(
            new RequiredExperienceId($model->id),
            new RequiredExperiencePositionId($model->position?->id ?? $model->position_id),
            new RequiredExperienceName($model->name),
            new RequiredExperienceDurationMonths($model->duration_months),
            new RequiredExperienceDescription($model->description),
            new RequiredExperienceCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new RequiredExperienceUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(RequiredExperience $requiredExperience): array
    {
        $array = [];

        if ($requiredExperience->positionId()->value()) {
            $array['position_id'] = $requiredExperience->positionId()->value();
        }

        if ($requiredExperience->name()->value()) {
            $array['name'] = $requiredExperience->name()->value();
        }

        if ($requiredExperience->durationMonths()->value()) {
            $array['duration_months'] = $requiredExperience->durationMonths()->value();
        }

        if ($requiredExperience->description()->value()) {
            $array['description'] = $requiredExperience->description()->value();
        }

        return $array;
    }
}
