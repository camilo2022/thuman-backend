<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\FormType\Contracts\FormTypeContract;
use App\Models\FormType as FormTypeModel;
use App\Domain\FormType\FormType;
use App\Domain\FormType\Values\FormTypeCreatedAt;
use App\Domain\FormType\Values\FormTypeDeletedAt;
use App\Domain\FormType\Values\FormTypeId;
use App\Domain\FormType\Values\FormTypeName;
use App\Domain\FormType\Values\FormTypeDescription;
use App\Domain\FormType\Values\FormTypeItemId;
use App\Domain\FormType\Values\FormTypeSettings;
use App\Domain\FormType\Values\FormTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FormTypeRepository implements FormTypeContract
{
    private $formTypeModel;

    public function __construct()
    {
        $this->formTypeModel = new FormTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $formTypes = $this->formTypeModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 18)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $formTypes->paginate($perPage ?? $formTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los motivos solicitud de personal:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los motivos solicitud de personal:' . $e->getMessage());
        }
    }

    public function find(FormTypeId $formTypeId): ?FormType
    {
        try {
            $model = $this->formTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 18)
                ->whereKey($formTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El motivo solicitud de personal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el motivo solicitud de personal:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el motivo solicitud de personal:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): FormType
    {
        return new FormType(
            new FormTypeId($model->id),
            new FormTypeItemId($model->item?->id ?? $model->item_id),
            new FormTypeName($model->name),
            new FormTypeDescription($model->description),
            new FormTypeSettings($model->settings),
            new FormTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FormTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FormTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
