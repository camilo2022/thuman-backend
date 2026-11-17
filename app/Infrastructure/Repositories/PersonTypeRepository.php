<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\PersonType\Contracts\PersonTypeContract;
use App\Models\PersonType as PersonTypeModel;
use App\Domain\PersonType\PersonType;
use App\Domain\PersonType\Values\PersonTypeCreatedAt;
use App\Domain\PersonType\Values\PersonTypeDeletedAt;
use App\Domain\PersonType\Values\PersonTypeId;
use App\Domain\PersonType\Values\PersonTypeName;
use App\Domain\PersonType\Values\PersonTypeDescription;
use App\Domain\PersonType\Values\PersonTypeItemId;
use App\Domain\PersonType\Values\PersonTypeRelationships;
use App\Domain\PersonType\Values\PersonTypeSettings;
use App\Domain\PersonType\Values\PersonTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PersonTypeRepository implements PersonTypeContract
{
    private $personTypeModel;

    public function __construct()
    {
        $this->personTypeModel = new PersonTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $personTypes = $this->personTypeModel->withTrashed()
                ->with(['item', 'document_types'])
                ->where('item_id', 5)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $personTypes->paginate($perPage ?? $personTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de personas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de personas: ' . $e->getMessage());
        }
    }

    public function find(PersonTypeId $personTypeId): ?PersonType
    {
        try {
            $model = $this->personTypeModel->withTrashed()
                ->with(['item', 'document_types'])->where('item_id', 5)
                ->whereKey($personTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de persona no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de persona: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): PersonType
    {
        return new PersonType(
            new PersonTypeId($model->id),
            new PersonTypeItemId($model->item?->id ?? $model->item_id),
            new PersonTypeName($model->name),
            new PersonTypeDescription($model->description),
            new PersonTypeSettings($model->settings),
            new PersonTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PersonTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PersonTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new PersonTypeRelationships($model->document_types)
        );
    }
}
