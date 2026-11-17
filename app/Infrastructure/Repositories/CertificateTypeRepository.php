<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\CertificateType\Contracts\CertificateTypeContract;
use App\Models\CertificateType as CertificateTypeModel;
use App\Domain\CertificateType\CertificateType;
use App\Domain\CertificateType\Values\CertificateTypeCreatedAt;
use App\Domain\CertificateType\Values\CertificateTypeDeletedAt;
use App\Domain\CertificateType\Values\CertificateTypeId;
use App\Domain\CertificateType\Values\CertificateTypeName;
use App\Domain\CertificateType\Values\CertificateTypeDescription;
use App\Domain\CertificateType\Values\CertificateTypeItemId;
use App\Domain\CertificateType\Values\CertificateTypeRelationships;
use App\Domain\CertificateType\Values\CertificateTypeSettings;
use App\Domain\CertificateType\Values\CertificateTypeUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CertificateTypeRepository implements CertificateTypeContract
{
    private $absenceTypeModel;

    public function __construct()
    {
        $this->absenceTypeModel = new CertificateTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $absenceTypes = $this->absenceTypeModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 45)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $absenceTypes->paginate($perPage ?? $absenceTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de certificados: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de certificados: ' . $e->getMessage());
        }
    }

    public function find(CertificateTypeId $absenceTypeId): ?CertificateType
    {
        try {
            $model = $this->absenceTypeModel->withTrashed()
                ->with(['item'])->where('item_id', 45)
                ->whereKey($absenceTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de certificado no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de certificado: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de certificado: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): CertificateType
    {
        return new CertificateType(
            new CertificateTypeId($model->id),
            new CertificateTypeItemId($model->item?->id ?? $model->item_id),
            new CertificateTypeName($model->name),
            new CertificateTypeDescription($model->description),
            new CertificateTypeSettings($model->settings),
            new CertificateTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new CertificateTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new CertificateTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new CertificateTypeRelationships($model->item)
        );
    }
}
