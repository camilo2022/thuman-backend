<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\BloodType\Contracts\BloodTypeContract;
use App\Models\BloodType as BloodTypeModel;
use App\Domain\BloodType\BloodType;
use App\Domain\BloodType\Values\BloodTypeCreatedAt;
use App\Domain\BloodType\Values\BloodTypeDeletedAt;
use App\Domain\BloodType\Values\BloodTypeId;
use App\Domain\BloodType\Values\BloodTypeName;
use App\Domain\BloodType\Values\BloodTypeDescription;
use App\Domain\BloodType\Values\BloodTypeItemId;
use App\Domain\BloodType\Values\BloodTypeSettings;
use App\Domain\BloodType\Values\BloodTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class BloodTypeRepository implements BloodTypeContract
{
    private $bloodTypeModel;

    public function __construct()
    {
        $this->bloodTypeModel = new BloodTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $bloodTypes = $this->bloodTypeModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 11)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $bloodTypes->paginate($perPage ?? $bloodTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de sangre:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de sangre:' . $e->getMessage());
        }
    }

    public function find(BloodTypeId $bloodTypeId): ?BloodType
    {
        try {
            $model = $this->bloodTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 11)
                ->whereKey($bloodTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de sangre no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de sangre:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de sangre:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): BloodType
    {
        return new BloodType(
            new BloodTypeId($model->id),
            new BloodTypeItemId($model->item?->id ?? $model->item_id),
            new BloodTypeName($model->name),
            new BloodTypeDescription($model->description),
            new BloodTypeSettings($model->settings),
            new BloodTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new BloodTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new BloodTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
