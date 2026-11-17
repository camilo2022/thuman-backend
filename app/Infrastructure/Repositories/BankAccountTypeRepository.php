<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\BankAccountType\Contracts\BankAccountTypeContract;
use App\Models\BankAccountType as BankAccountTypeModel;
use App\Domain\BankAccountType\BankAccountType;
use App\Domain\BankAccountType\Values\BankAccountTypeCreatedAt;
use App\Domain\BankAccountType\Values\BankAccountTypeDeletedAt;
use App\Domain\BankAccountType\Values\BankAccountTypeId;
use App\Domain\BankAccountType\Values\BankAccountTypeName;
use App\Domain\BankAccountType\Values\BankAccountTypeDescription;
use App\Domain\BankAccountType\Values\BankAccountTypeItemId;
use App\Domain\BankAccountType\Values\BankAccountTypeSettings;
use App\Domain\BankAccountType\Values\BankAccountTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class BankAccountTypeRepository implements BankAccountTypeContract
{
    private $bankAccountTypeModel;

    public function __construct()
    {
        $this->bankAccountTypeModel = new BankAccountTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $bankAccountTypes = $this->bankAccountTypeModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 15)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $bankAccountTypes->paginate($perPage ?? $bankAccountTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de cuenta bancaria: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de cuenta bancaria:' . $e->getMessage());
        }
    }

    public function find(BankAccountTypeId $bankAccountTypeId): ?BankAccountType
    {
        try {
            $model = $this->bankAccountTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 15)
                ->findOrFail($bankAccountTypeId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de cuenta bancaria no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de cuenta bancaria:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de cuenta bancaria.' . $e->getMessage());
        }
    }

    private function mapToEntity($model): BankAccountType
    {
        return new BankAccountType(
            new BankAccountTypeId($model->id),
            new BankAccountTypeItemId($model->item_id),
            new BankAccountTypeName($model->name),
            new BankAccountTypeDescription($model->description),
            new BankAccountTypeSettings($model->settings),
            new BankAccountTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new BankAccountTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new BankAccountTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
