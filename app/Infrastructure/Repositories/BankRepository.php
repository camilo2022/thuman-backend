<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Bank\Contracts\BankContract;
use App\Models\Bank as BankModel;
use App\Domain\Bank\Bank;
use App\Domain\Bank\Values\BankCountryId;
use App\Domain\Bank\Values\BankCreatedAt;
use App\Domain\Bank\Values\BankDeletedAt;
use App\Domain\Bank\Values\BankId;
use App\Domain\Bank\Values\BankName;
use App\Domain\Bank\Values\BankDescription;
use App\Domain\Bank\Values\BankItemId;
use App\Domain\Bank\Values\BankRelationships;
use App\Domain\Bank\Values\BankSettings;
use App\Domain\Bank\Values\BankUpdatedAt;
use App\Http\Requests\Bank\BankAllRequest;
use App\Models\BankCertificate;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class BankRepository implements BankContract
{
    private $bankModel;

    public function __construct()
    {
        $this->bankModel = new BankModel;
    }

    public function all(string|null $search = null, int|null $perPage, BankAllRequest $request): LengthAwarePaginator
    {
        try {
            $banks = $this->bankModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'countries'])
                ->where('item_id', 14)
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereHas('countries', function ($sq) use ($request) {
                        $sq->where('id', $request->integer('country_id'));
                    });
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $banks->paginate($perPage ?? $banks->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los bancos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los bancos: ' . $e->getMessage());
        }
    }

    public function find(BankId $bankId): ?Bank
    {
        try {
            $model = $this->bankModel->withTrashed()
                ->with(['item', 'countries'])->where('item_id', 14)
                ->findOrFail($bankId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El banco no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el banco: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el banco: ' . $e->getMessage());
        }
    }

    public function store(Bank $bank): Bank
    {
        try {
            $model = $this->bankModel
                ->create($this->mapToModelData($bank));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el banco: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el banco: ' . $e->getMessage());
        }
    }

    public function update(Bank $bank, BankId $bankId): Bank
    {
        try {
            $model = tap($this->bankModel->where('item_id', 14)->whereKey($bankId->value())->firstOrFail())
                ->update($this->mapToModelData($bank))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El banco no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el banco: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el banco: ' . $e->getMessage());
        }
    }

    public function toggle(Bank $bank, BankId $bankId): Bank
    {
        try {
            $model = $this->bankModel->where('item_id', 14)->whereKey($bankId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $bank->countryId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El banco no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el banco: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el banco: ' . $e->getMessage());
        }
    }

    public function delete(BankId $bankId): Bank
    {
        try {
            $model = $this->bankModel->where('item_id', 14)->whereKey($bankId->value())->firstOrFail();

            $isUsed = BankCertificate::where('bank_id', $bankId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El banco no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el banco: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el banco: ' . $e->getMessage());
        }
    }

    public function restore(BankId $bankId): Bank
    {
        try {
            $model = tap($this->bankModel->withTrashed()->where('item_id', 14)->whereKey($bankId->value())->firstOrFail(), fn($bank) => $bank->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El banco no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el banco: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el banco: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Bank
    {
        return new Bank(
            new BankId($model->id),
            new BankItemId($model->item_id),
            new BankName($model->name),
            new BankDescription($model->description),
            new BankSettings($model->settings),
            new BankCountryId(),
            new BankCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new BankUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new BankDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new BankRelationships($model->countries)
        );
    }

    private function mapToModelData(Bank $bank): array
    {
        $array = [];

        if ($bank->itemId()->value()) {
            $array['item_id'] = $bank->itemId()->value();
        }

        if ($bank->name()->value()) {
            $array['name'] = $bank->name()->value();
        }

        if ($bank->description()->value()) {
            $array['description'] = $bank->description()->value();
        }

        if ($bank->settings()->value()) {
            $array['settings'] = $bank->settings()->value();
        }

        return $array;
    }
}
