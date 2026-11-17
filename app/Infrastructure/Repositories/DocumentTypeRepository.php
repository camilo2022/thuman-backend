<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\DocumentType\Contracts\DocumentTypeContract;
use App\Models\DocumentType as DocumentTypeModel;
use App\Domain\DocumentType\DocumentType;
use App\Domain\DocumentType\Values\DocumentTypeCreatedAt;
use App\Domain\DocumentType\Values\DocumentTypeDeletedAt;
use App\Domain\DocumentType\Values\DocumentTypeId;
use App\Domain\DocumentType\Values\DocumentTypeName;
use App\Domain\DocumentType\Values\DocumentTypeDescription;
use App\Domain\DocumentType\Values\DocumentTypeItemId;
use App\Domain\DocumentType\Values\DocumentTypePersonTypeId;
use App\Domain\DocumentType\Values\DocumentTypeRelationships;
use App\Domain\DocumentType\Values\DocumentTypeSettings;
use App\Domain\DocumentType\Values\DocumentTypeUpdatedAt;
use App\Http\Requests\DocumentType\DocumentTypeAllRequest;
use App\Models\Entity;
use App\Models\Person;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class DocumentTypeRepository implements DocumentTypeContract
{
    private $documentTypeModel;

    public function __construct()
    {
        $this->documentTypeModel = new DocumentTypeModel;
    }

    public function all(string|null $search = null, int|null $perPage, DocumentTypeAllRequest $request): LengthAwarePaginator
    {
        try {
            $documentTypes = $this->documentTypeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'person_type'])
                ->where('item_id', 4)
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereJsonContains('settings->validations', [['country_id' => $request->integer('country_id'), 'deleted_at' => null]]);
                })
                ->orderBy('id', 'asc');

            return $documentTypes->paginate($perPage ?? $documentTypes->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los tipos de documentos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los tipos de documentos: ' . $e->getMessage());
        }
    }

    public function find(DocumentTypeId $documentTypeId): ?DocumentType
    {
        try {
            $model = $this->documentTypeModel->withTrashed()
                ->with(['item', 'person_type'])->where('item_id', 4)
                ->whereKey($documentTypeId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de documento no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el tipo de documento: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el tipo de documento: ' . $e->getMessage());
        }
    }

    public function store(DocumentType $documentType): DocumentType
    {
        try {
            $model = $this->documentTypeModel
                ->create($this->mapToModelData($documentType));

            $model->person_type()->sync(array_filter([
                $documentType->personTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el tipo de documento: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el tipo de documento: ' . $e->getMessage());
        }
    }

    public function update(DocumentType $documentType, DocumentTypeId $documentTypeId): DocumentType
    {
        try {
            $model = tap($this->documentTypeModel->where('item_id', 4)->whereKey($documentTypeId->value())->firstOrFail())
                ->update($this->mapToModelData($documentType))
                ->refresh();

            $model->person_type()->sync(array_filter([
                $documentType->personTypeId()->value()
            ]));

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de documento no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el tipo de documento: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el tipo de documento: ' . $e->getMessage());
        }
    }

    public function delete(DocumentTypeId $documentTypeId): DocumentType
    {
        try {
            $model = $this->documentTypeModel
                ->where('item_id', 4)
                ->whereKey($documentTypeId->value())
                ->firstOrFail();

            $isUsed = Entity::where('document_type_id', $model->id)->exists() || Person::where('document_type_id', $model->id)->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de documento no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al inactivar el tipo de documento: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al inactivar el tipo de documento: ' . $e->getMessage());
        }
    }

    public function restore(DocumentTypeId $documentTypeId): DocumentType
    {
        try {
            $model = tap($this->documentTypeModel->withTrashed()->where('item_id', 4)->whereKey($documentTypeId->value())->firstOrFail(), fn($documentType) => $documentType->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El tipo de documento no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el tipo de documento: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el tipo de documento: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): DocumentType
    {
        return new DocumentType(
            new DocumentTypeId($model->id),
            new DocumentTypePersonTypeId($model->person_type?->first()?->id),
            new DocumentTypeItemId($model->item?->id ?? $model->item_id),
            new DocumentTypeName($model->name),
            new DocumentTypeDescription($model->description),
            new DocumentTypeSettings($model->settings),
            new DocumentTypeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new DocumentTypeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new DocumentTypeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new DocumentTypeRelationships($model->person_type?->first())
        );
    }

    private function mapToModelData(DocumentType $documentType): array
    {
        $array = [];

        if ($documentType->itemId()->value()) {
            $array['item_id'] = $documentType->itemId()->value();
        }

        if ($documentType->name()->value()) {
            $array['name'] = $documentType->name()->value();
        }

        if ($documentType->description()->value()) {
            $array['description'] = $documentType->description()->value();
        }

        if ($documentType->settings()->value()) {
            $array['settings'] = (object) [
                'abreviature' => $documentType->settings()->abreviature(),
                'validations' => $documentType->settings()->validations(false)
            ];
        }

        return $array;
    }
}
