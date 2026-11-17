<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Skill\Contracts\SkillContract;
use App\Models\Skill as SkillModel;
use App\Domain\Skill\Skill;
use App\Domain\Skill\Values\SkillCreatedAt;
use App\Domain\Skill\Values\SkillDeletedAt;
use App\Domain\Skill\Values\SkillId;
use App\Domain\Skill\Values\SkillName;
use App\Domain\Skill\Values\SkillDescription;
use App\Domain\Skill\Values\SkillItemId;
use App\Domain\Skill\Values\SkillSettings;
use App\Domain\Skill\Values\SkillSkillTypeId;
use App\Domain\Skill\Values\SkillUpdatedAt;
use App\Http\Requests\Skill\SkillAllRequest;
use App\Models\ProfessionalSkill;
use App\Models\RequiredSkill;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SkillRepository implements SkillContract
{
    private $skillModel;

    public function __construct()
    {
        $this->skillModel = new SkillModel;
    }

    public function all(string|null $search = null, int|null $perPage, SkillAllRequest $request): LengthAwarePaginator
    {
        try {
            $skills = $this->skillModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'skill_type'])
                ->where('item_id', 29)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('skill_type_id'), function ($q) use ($request) {
                    $q->whereHas('skill_type', fn($sq) => $sq->where('id', $request->integer('skill_type_id')));
                })
                ->orderBy('id', 'asc');

            return $skills->paginate($perPage ?? $skills->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las habilidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las habilidades: ' . $e->getMessage());
        }
    }

    public function find(SkillId $skillId): ?Skill
    {
        try {
            $model = $this->skillModel->withTrashed()
                ->with(['item', 'skill_type'])->where('item_id', 29)
                ->whereKey($skillId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la habilidad: ' . $e->getMessage());
        }
    }

    public function store(Skill $skill): Skill
    {
        try {
            $model = $this->skillModel
                ->create($this->mapToModelData($skill));

            $model->skill_type()->sync(array_filter([
                $skill->skillTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la habilidad: ' . $e->getMessage());
        }
    }

    public function update(Skill $skill, SkillId $skillId): Skill
    {
        try {
            $model = tap($this->skillModel->where('item_id', 29)->whereKey($skillId->value())->firstOrFail())
                ->update($this->mapToModelData($skill))
                ->refresh();

            $model->skill_type()->sync(array_filter([
                $skill->skillTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la habilidad: ' . $e->getMessage());
        }
    }

    public function delete(SkillId $skillId): Skill
    {
        try {

            $model = $this->skillModel
                ->where('item_id', 29)
                ->whereKey($skillId->value())
                ->firstOrFail();

            $isUsed = ProfessionalSkill::where('skill_id', $model->id)->exists() || RequiredSkill::where('skill_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La habilidad no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la habilidad: ' . $e->getMessage());
        }
    }

    public function restore(SkillId $skillId): Skill
    {
        try {
            $model = tap($this->skillModel->withTrashed()->where('item_id', 29)->whereKey($skillId->value())->firstOrFail(), fn($skill) => $skill->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la habilidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Skill
    {
        return new Skill(
            new SkillId($model->id),
            new SkillSkillTypeId($model->skill_type->first()?->id),
            new SkillItemId($model->item?->id ?? $model->item_id),
            new SkillName($model->name),
            new SkillDescription($model->description),
            new SkillSettings($model->settings),
            new SkillCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SkillUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SkillDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Skill $skill): array
    {
        $array = [];

        if ($skill->itemId()->value()) {
            $array['item_id'] = $skill->itemId()->value();
        }

        if ($skill->name()->value()) {
            $array['name'] = $skill->name()->value();
        }

        if ($skill->description()->value()) {
            $array['description'] = $skill->description()->value();
        }

        if ($skill->settings()->value()) {
            $array['settings'] = $skill->settings()->value();
        }

        return $array;
    }
}
