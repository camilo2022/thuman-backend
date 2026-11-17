<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\TerminationType\Reason\Contracts\ReasonContract;
use App\Models\Reason as ReasonModel;
use App\Domain\TerminationType\Reason\Reason;
use App\Domain\TerminationType\Reason\Values\ReasonCreatedAt;
use App\Domain\TerminationType\Reason\Values\ReasonDeletedAt;
use App\Domain\TerminationType\Reason\Values\ReasonId;
use App\Domain\TerminationType\Reason\Values\ReasonName;
use App\Domain\TerminationType\Reason\Values\ReasonDescription;
use App\Domain\TerminationType\Reason\Values\ReasonItemId;
use App\Domain\TerminationType\Reason\Values\ReasonTerminationTypeId;
use App\Domain\TerminationType\Reason\Values\ReasonRelationships;
use App\Domain\TerminationType\Reason\Values\ReasonSettings;
use App\Domain\TerminationType\Reason\Values\ReasonUpdatedAt;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\Reason\ReasonAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ReasonRepository implements ReasonContract
{
    private $reasonModel;

    public function __construct()
    {
        $this->reasonModel = new ReasonModel;
    }

    public function all(string|null $search = null, int|null $perPage, ReasonAllRequest $request, TerminationTypeId $terminationTypeId): LengthAwarePaginator
    {
        try {
            $reasons = $this->reasonModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'termination_type'])
                ->where('item_id', 48)
                ->whereHas('termination_type', fn ($r) => $r->where('id', $terminationTypeId->value()))
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $reasons->paginate($perPage ?? $reasons->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las razones de terminación de contratos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las razones de terminación de contratos: ' . $e->getMessage());
        }
    }

    public function find(ReasonId $reasonId): ?Reason
    {
        try {
            $model = $this->reasonModel->withTrashed()
                ->with(['item', 'termination_type'])->where('item_id', 48)
                ->whereKey($reasonId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La razón de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la razón de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la razón de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function store(Reason $reason): Reason
    {
        try {
            $model = $this->reasonModel
                ->create($this->mapToModelData($reason));

            $model->termination_type()->sync(array_filter([
                $reason->terminationTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la razón de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la razón de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function update(Reason $reason, ReasonId $reasonId): Reason
    {
        try {
            $model = tap($this->reasonModel->where('item_id', 48)->whereKey($reasonId->value())->firstOrFail())
                ->update($this->mapToModelData($reason))
                ->refresh();

            $model->termination_type()->sync(array_filter([
                $reason->terminationTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La razón de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la razón de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la razón de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function delete(ReasonId $reasonId): Reason
    {
        try {
            $model = tap($this->reasonModel->where('item_id', 48)->whereKey($reasonId->value())->firstOrFail(), fn($reason) => $reason->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La razón de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la razón de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la razón de terminación de contrato: ' . $e->getMessage());
        }
    }

    public function restore(ReasonId $reasonId): Reason
    {
        try {
            $model = tap($this->reasonModel->withTrashed()->where('item_id', 48)->whereKey($reasonId->value())->firstOrFail(), fn($reason) => $reason->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La razón de terminación de contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la razón de terminación de contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la razón de terminación de contrato: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Reason
    {
        return new Reason(
            new ReasonId($model->id),
            new ReasonTerminationTypeId($model->termination_type?->first()?->id),
            new ReasonItemId($model->item?->id ?? $model->item_id),
            new ReasonName($model->name),
            new ReasonDescription($model->description),
            new ReasonSettings($model->settings),
            new ReasonCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ReasonUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ReasonDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new ReasonRelationships($model->termination_type?->first())
        );
    }

    private function mapToModelData(Reason $reason): array
    {
        $array = [];

        if ($reason->itemId()->value()) {
            $array['item_id'] = $reason->itemId()->value();
        }

        if ($reason->name()->value()) {
            $array['name'] = $reason->name()->value();
        }

        if ($reason->description()->value()) {
            $array['description'] = $reason->description()->value();
        }

        return $array;
    }
}
