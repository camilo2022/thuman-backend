<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Position\AcademicRequirement\Contracts\AcademicRequirementContract;
use App\Models\AcademicRequirement as AcademicRequirementModel;
use App\Domain\Position\AcademicRequirement\AcademicRequirement;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementAcademicLevelId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementAcademicTitle;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementCreatedAt;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementFormationStatusId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementPositionId;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementUpdatedAt;
use App\Domain\Position\Values\PositionId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AcademicRequirementRepository implements AcademicRequirementContract
{
    private $academicRequirementModel;

    public function __construct()
    {
        $this->academicRequirementModel = new AcademicRequirementModel;
    }

    public function all(string|null $search = null, int|null $perPage, PositionId $positionId): LengthAwarePaginator
    {
        try {
            $academicRequirements = $this->academicRequirementModel
                ->with(['position', 'academic_level', 'formation_status'])
                ->where('position_id', $positionId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $academicRequirements->paginate($perPage ?? $academicRequirements->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los estudios requeridos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los estudios requeridos: ' . $e->getMessage());
        }
    }

    public function find(AcademicRequirementId $academicRequirementId): ?AcademicRequirement
    {
        try {
            $model = $this->academicRequirementModel
                ->with(['position', 'academic_level', 'formation_status'])
                ->findOrFail($academicRequirementId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estudio requerido no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el estudio requerido: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el estudio requerido: ' . $e->getMessage());
        }
    }

    public function store(AcademicRequirement $academicRequirement): AcademicRequirement
    {
        try {
            $model = $this->academicRequirementModel
                ->create($this->mapToModelData($academicRequirement));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el estudio requerido: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el estudio requerido: ' . $e->getMessage());
        }
    }

    public function update(AcademicRequirement $academicRequirement, AcademicRequirementId $academicRequirementId): AcademicRequirement
    {
        try {
            $model = tap($this->academicRequirementModel->findOrFail($academicRequirementId->value()))
                ->update($this->mapToModelData($academicRequirement))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estudio requerido no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el estudio requerido: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el estudio requerido: ' . $e->getMessage());
        }
    }

    public function delete(AcademicRequirementId $academicRequirementId): void
    {
        try {
            $this->academicRequirementModel->findOrFail($academicRequirementId->value())
                ->delete();

        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estudio requerido no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el estudio requerido: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el estudio requerido: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): AcademicRequirement
    {
        return new AcademicRequirement(
            new AcademicRequirementId($model->id),
            new AcademicRequirementPositionId($model->position?->id ?? $model->position_id),
            new AcademicRequirementAcademicLevelId($model->academic_level?->id ?? $model->academic_level_id),
            new AcademicRequirementAcademicTitle($model->academic_title),
            new AcademicRequirementFormationStatusId($model->formation_status?->id ?? $model->formation_status_id),
            new AcademicRequirementCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AcademicRequirementUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(AcademicRequirement $academicRequirement): array
    {
        $array = [];

        if ($academicRequirement->positionId()->value()) {
            $array['position_id'] = $academicRequirement->positionId()->value();
        }

        if ($academicRequirement->academicLevelId()->value()) {
            $array['academic_level_id'] = $academicRequirement->academicLevelId()->value();
        }

        if ($academicRequirement->academicTitle()->value()) {
            $array['academic_title'] = $academicRequirement->academicTitle()->value();
        }

        if ($academicRequirement->formationStatusId()->value()) {
            $array['formation_status_id'] = $academicRequirement->formationStatusId()->value();
        }

        return $array;
    }
}
