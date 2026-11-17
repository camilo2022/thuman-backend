<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\FormationStatus\Contracts\FormationStatusContract;
use App\Models\FormationStatus as FormationStatusModel;
use App\Domain\FormationStatus\FormationStatus;
use App\Domain\FormationStatus\Values\FormationStatusCreatedAt;
use App\Domain\FormationStatus\Values\FormationStatusDeletedAt;
use App\Domain\FormationStatus\Values\FormationStatusId;
use App\Domain\FormationStatus\Values\FormationStatusName;
use App\Domain\FormationStatus\Values\FormationStatusDescription;
use App\Domain\FormationStatus\Values\FormationStatusItemId;
use App\Domain\FormationStatus\Values\FormationStatusSettings;
use App\Domain\FormationStatus\Values\FormationStatusUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FormationStatusRepository implements FormationStatusContract
{
    private $formationStatusModel;

    public function __construct()
    {
        $this->formationStatusModel = new FormationStatusModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $formationStatuses = $this->formationStatusModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 7)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $formationStatuses->paginate($perPage ?? $formationStatuses->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los estatos de formacion: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los estatos de formacion: ' . $e->getMessage());
        }
    }

    public function find(FormationStatusId $formationStatusId): ?FormationStatus
    {
        try {
            $model = $this->formationStatusModel->withTrashed()
                ->with(['item'])->where('item_id', 7)
                ->whereKey($formationStatusId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El estado de formacion no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el estado de formacion: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el estado de formacion: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): FormationStatus
    {
        return new FormationStatus(
            new FormationStatusId($model->id),
            new FormationStatusItemId($model->item?->id ?? $model->item_id),
            new FormationStatusName($model->name),
            new FormationStatusDescription($model->description),
            new FormationStatusSettings($model->settings),
            new FormationStatusCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FormationStatusUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FormationStatusDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
