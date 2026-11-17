<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Relationship\Contracts\RelationshipContract;
use App\Models\Relationship as RelationshipModel;
use App\Domain\Relationship\Relationship;
use App\Domain\Relationship\Values\RelationshipCreatedAt;
use App\Domain\Relationship\Values\RelationshipDeletedAt;
use App\Domain\Relationship\Values\RelationshipId;
use App\Domain\Relationship\Values\RelationshipName;
use App\Domain\Relationship\Values\RelationshipDescription;
use App\Domain\Relationship\Values\RelationshipItemId;
use App\Domain\Relationship\Values\RelationshipSettings;
use App\Domain\Relationship\Values\RelationshipUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RelationshipRepository implements RelationshipContract
{
    private $relationshipModel;

    public function __construct()
    {
        $this->relationshipModel = new RelationshipModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $relationships = $this->relationshipModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 52)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $relationships->paginate($perPage ?? $relationships->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los parentescos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los parentescos: ' . $e->getMessage());
        }
    }

    public function find(RelationshipId $relationshipId): ?Relationship
    {
        try {
            $model = $this->relationshipModel->withTrashed()
                ->with(['item'])->where('item_id', 52)
                ->whereKey($relationshipId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El parentesco no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el parentesco: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el parentesco: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Relationship
    {
        return new Relationship(
            new RelationshipId($model->id),
            new RelationshipItemId($model->item?->id ?? $model->item_id),
            new RelationshipName($model->name),
            new RelationshipDescription($model->description),
            new RelationshipSettings($model->settings),
            new RelationshipCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new RelationshipUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new RelationshipDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
