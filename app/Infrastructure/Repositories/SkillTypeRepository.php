<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\SkillType\Contracts\SkillTypeContract;
use App\Models\SkillType as SkillTypeModel;
use App\Domain\SkillType\SkillType;
use App\Domain\SkillType\Values\SkillTypeCreatedAt;
use App\Domain\SkillType\Values\SkillTypeDeletedAt;
use App\Domain\SkillType\Values\SkillTypeId;
use App\Domain\SkillType\Values\SkillTypeName;
use App\Domain\SkillType\Values\SkillTypeDescription;
use App\Domain\SkillType\Values\SkillTypeItemId;
use App\Domain\SkillType\Values\SkillTypeSettings;
use App\Domain\SkillType\Values\SkillTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SkillTypeRepository implements SkillTypeContract
{
    private $skillTypeModel;

    public function __construct()
    {
        $this->skillTypeModel = new SkillTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $skillTypes = $this->skillTypeModel->withTrashed()
                ->with(['item', 'skills'])
                ->where('item_id', 6)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $skillTypes->paginate($perPage ?? $skillTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de habilidades: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de habilidades: ' . $e->getMessage());
        }
    }

    public function find(SkillTypeId $skillTypeId): ?SkillType
    {
        try {
            $model = $this->skillTypeModel->withTrashed()
                ->with(['item', 'skills'])->where('item_id', 6)
                ->whereKey($skillTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de habilidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de habilidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de habilidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): SkillType
    {
        return new SkillType(
            new SkillTypeId($model->id),
            new SkillTypeItemId($model->item?->id ?? $model->item_id),
            new SkillTypeName($model->name),
            new SkillTypeDescription($model->description),
            new SkillTypeSettings($model->settings),
            new SkillTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SkillTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SkillTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
