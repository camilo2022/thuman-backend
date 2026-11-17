<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\WorkDay\Contracts\WorkDayContract;
use App\Models\WorkDay as WorkDayModel;
use App\Domain\WorkDay\WorkDay;
use App\Domain\WorkDay\Values\WorkDayCreatedAt;
use App\Domain\WorkDay\Values\WorkDayDeletedAt;
use App\Domain\WorkDay\Values\WorkDayId;
use App\Domain\WorkDay\Values\WorkDayName;
use App\Domain\WorkDay\Values\WorkDayDescription;
use App\Domain\WorkDay\Values\WorkDayItemId;
use App\Domain\WorkDay\Values\WorkDaySettings;
use App\Domain\WorkDay\Values\WorkDayUpdatedAt;
use App\Http\Requests\WorkDay\WorkDayAllRequest;
use App\Models\Vacant;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class WorkDayRepository implements WorkDayContract
{
    private $workDayModel;

    public function __construct()
    {
        $this->workDayModel = new WorkDayModel;
    }

    public function all(string|null $search = null, int|null $perPage, WorkDayAllRequest $request): LengthAwarePaginator
    {
        try {
            $workDays = $this->workDayModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 2)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $workDays->paginate($perPage ?? $workDays->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las jornadas laborales: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las jornadas laborales: ' . $e->getMessage());
        }
    }

    public function find(WorkDayId $workDayId): ?WorkDay
    {
        try {
            $model = $this->workDayModel->withTrashed()
                ->with(['item'])->where('item_id', 2)
                ->whereKey($workDayId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La jornada laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la jornada laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la jornada laboral: ' . $e->getMessage());
        }
    }

    public function store(WorkDay $workDay): WorkDay
    {
        try {
            $model = $this->workDayModel
                ->create($this->mapToModelData($workDay));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la jornada laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la jornada laboral: ' . $e->getMessage());
        }
    }

    public function update(WorkDay $workDay, WorkDayId $workDayId): WorkDay
    {
        try {
            $model = tap($this->workDayModel->where('item_id', 2)->whereKey($workDayId->value())->firstOrFail())
                ->update($this->mapToModelData($workDay))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La jornada laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la jornada laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la jornada laboral: ' . $e->getMessage());
        }
    }

    public function delete(WorkDayId $workDayId): WorkDay
    {
        try {

            $model = $this->workDayModel
                ->where('item_id', 2)
                ->whereKey($workDayId->value())
                ->firstOrFail();

            $isUsed = Vacant::where('work_day_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La jornada laboral no existe: ' . $e->getMessage());
        }  catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la jornada laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la jornada laboral: ' . $e->getMessage());
        }
    }

    public function restore(WorkDayId $workDayId): WorkDay
    {
        try {
            $model = tap($this->workDayModel->withTrashed()->where('item_id', 2)->whereKey($workDayId->value())->firstOrFail(), fn($workDay) => $workDay->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La jornada laboral no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la jornada laboral: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la jornada laboral: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): WorkDay
    {
        return new WorkDay(
            new WorkDayId($model->id),
            new WorkDayItemId($model->item_id),
            new WorkDayName($model->name),
            new WorkDayDescription($model->description),
            new WorkDaySettings($model->settings),
            new WorkDayCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new WorkDayUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new WorkDayDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(WorkDay $workDay): array
    {
        $array = [];

        if ($workDay->itemId()->value()) {
            $array['item_id'] = $workDay->itemId()->value();
        }

        if ($workDay->name()->value()) {
            $array['name'] = $workDay->name()->value();
        }

        if ($workDay->description()->value()) {
            $array['description'] = $workDay->description()->value();
        }

        if ($workDay->settings()->value()) {
            $array['settings'] = $workDay->settings()->value();
        }

        return $array;
    }
}
