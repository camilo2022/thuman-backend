<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Profile\ProfessionalSkill\Contracts\ProfessionalSkillContract;
use App\Models\ProfessionalSkill as ProfessionalSkillModel;
use App\Domain\Profile\ProfessionalSkill\ProfessionalSkill;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillCreatedAt;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillProfileId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillPercentage;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillSkillId;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillUpdatedAt;
use App\Domain\Profile\Values\ProfileId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ProfessionalSkillRepository implements ProfessionalSkillContract
{
    private $professionalSkillModel;

    public function __construct()
    {
        $this->professionalSkillModel = new ProfessionalSkillModel;
    }

    public function all(string|null $search = null, int|null $perPage, ProfileId $profileId): LengthAwarePaginator
    {
        try {
            $professionalSkills = $this->professionalSkillModel
                ->with(['profile', 'skill.skill_type'])
                ->where('profile_id', $profileId->value())
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $professionalSkills->paginate($perPage ?? $professionalSkills->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las habilidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las habilidades: ' . $e->getMessage());
        }
    }

    public function find(ProfessionalSkillId $professionalSkillId): ?ProfessionalSkill
    {
        try {
            $model = $this->professionalSkillModel
                ->with(['profile', 'skill.skill_type'])
                ->findOrFail($professionalSkillId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la habilidad: ' . $e->getMessage());
        }
    }

    public function store(ProfessionalSkill $professionalSkill): ProfessionalSkill
    {
        try {
            $model = $this->professionalSkillModel
                ->create($this->mapToModelData($professionalSkill));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la habilidad: ' . $e->getMessage());
        }
    }

    public function update(ProfessionalSkill $professionalSkill, ProfessionalSkillId $professionalSkillId): ProfessionalSkill
    {
        try {
            $model = tap($this->professionalSkillModel->findOrFail($professionalSkillId->value()))
                ->update($this->mapToModelData($professionalSkill))
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

    public function delete(ProfessionalSkillId $professionalSkillId): void
    {
        try {
            $this->professionalSkillModel->findOrFail($professionalSkillId->value())
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la habilidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): ProfessionalSkill
    {
        return new ProfessionalSkill(
            new ProfessionalSkillId($model->id),
            new ProfessionalSkillProfileId($model->profile?->id ?? $model->profile_id),
            new ProfessionalSkillSkillId($model->skill?->id ?? $model->skill_id),
            new ProfessionalSkillPercentage($model->percentage),
            new ProfessionalSkillCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ProfessionalSkillUpdatedAt($model->updated_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(ProfessionalSkill $professionalSkill): array
    {
        $array = [];

        if ($professionalSkill->profileId()->value()) {
            $array['profile_id'] = $professionalSkill->profileId()->value();
        }

        if ($professionalSkill->skillId()->value()) {
            $array['skill_id'] = $professionalSkill->skillId()->value();
        }

        if ($professionalSkill->percentage()->value()) {
            $array['percentage'] = $professionalSkill->percentage()->value();
        }

        return $array;
    }
}
