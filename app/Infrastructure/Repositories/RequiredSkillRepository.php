<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Position\RequiredSkill\Contracts\RequiredSkillContract;
use App\Models\RequiredSkill as RequiredSkillModel;
use App\Domain\Position\RequiredSkill\RequiredSkill;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillCreatedAt;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPositionId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillPercentage;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillSkillId;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillUpdatedAt;
use App\Domain\Position\Values\PositionId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RequiredSkillRepository implements RequiredSkillContract
{
    private $requiredSkillModel;

    public function __construct()
    {
        $this->requiredSkillModel = new RequiredSkillModel;
    }

    public function all(string|null $search = null, int|null $perPage, PositionId $positionId): LengthAwarePaginator
    {
        try {
            $requiredSkills = $this->requiredSkillModel
                ->with(['position', 'skill.skill_type'])
                ->where('position_id', $positionId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $requiredSkills->paginate($perPage ?? $requiredSkills->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las habilidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las habilidades: ' . $e->getMessage());
        }
    }

    public function find(RequiredSkillId $requiredSkillId): ?RequiredSkill
    {
        try {
            $model = $this->requiredSkillModel
                ->with(['position', 'skill.skill_type'])
                ->findOrFail($requiredSkillId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la habilidad: ' . $e->getMessage());
        }
    }

    public function store(RequiredSkill $requiredSkill): RequiredSkill
    {
        try {
            $model = $this->requiredSkillModel
                ->create($this->mapToModelData($requiredSkill));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la habilidad: ' . $e->getMessage());
        }
    }

    public function update(RequiredSkill $requiredSkill, RequiredSkillId $requiredSkillId): RequiredSkill
    {
        try {
            $model = tap($this->requiredSkillModel->findOrFail($requiredSkillId->value()))
                ->update($this->mapToModelData($requiredSkill))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la habilidad: ' . $e->getMessage());
        }
    }

    public function delete(RequiredSkillId $requiredSkillId): void
    {
        try {
            $this->requiredSkillModel->findOrFail($requiredSkillId->value())
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la habilidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): RequiredSkill
    {
        return new RequiredSkill(
            new RequiredSkillId($model->id),
            new RequiredSkillPositionId($model->position?->id ?? $model->position_id),
            new RequiredSkillSkillId($model->skill?->id ?? $model->skill_id),
            new RequiredSkillPercentage($model->percentage),
            new RequiredSkillCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new RequiredSkillUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(RequiredSkill $requiredSkill): array
    {
        $array = [];

        if ($requiredSkill->positionId()->value()) {
            $array['position_id'] = $requiredSkill->positionId()->value();
        }

        if ($requiredSkill->skillId()->value()) {
            $array['skill_id'] = $requiredSkill->skillId()->value();
        }

        if ($requiredSkill->percentage()->value()) {
            $array['percentage'] = $requiredSkill->percentage()->value();
        }

        return $array;
    }
}
