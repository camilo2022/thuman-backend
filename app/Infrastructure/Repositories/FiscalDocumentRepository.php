<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\FiscalDocument\Contracts\FiscalDocumentContract;
use App\Models\FiscalDocument as FiscalDocumentModel;
use App\Domain\FiscalDocument\FiscalDocument;
use App\Domain\FiscalDocument\Values\FiscalDocumentCountryId;
use App\Domain\FiscalDocument\Values\FiscalDocumentCreatedAt;
use App\Domain\FiscalDocument\Values\FiscalDocumentDeletedAt;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;
use App\Domain\FiscalDocument\Values\FiscalDocumentName;
use App\Domain\FiscalDocument\Values\FiscalDocumentDescription;
use App\Domain\FiscalDocument\Values\FiscalDocumentItemId;
use App\Domain\FiscalDocument\Values\FiscalDocumentRelationships;
use App\Domain\FiscalDocument\Values\FiscalDocumentSettings;
use App\Domain\FiscalDocument\Values\FiscalDocumentUpdatedAt;
use App\Http\Requests\FiscalDocument\FiscalDocumentAllRequest;
use App\Models\FiscalDocumentCertificate;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FiscalDocumentRepository implements FiscalDocumentContract
{
    private $fiscalDocumentModel;

    public function __construct()
    {
        $this->fiscalDocumentModel = new FiscalDocumentModel;
    }

    public function all(string|null $search = null, int|null $perPage, FiscalDocumentAllRequest $request): LengthAwarePaginator
    {
        try {
            $fiscalDocuments = $this->fiscalDocumentModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item', 'countries'])
                ->where('item_id', 31)
                ->when($request->filled('country_id'), function ($q) use ($request) {
                    $q->whereHas('countries', fn($r) => $r->where('id', $request->integer('country_id')));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $fiscalDocuments->paginate($perPage ?? $fiscalDocuments->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los documentos fiscales: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los documentos fiscales: ' . $e->getMessage());
        }
    }

    public function find(FiscalDocumentId $fiscalDocumentId): ?FiscalDocument
    {
        try {
            $model = $this->fiscalDocumentModel->withTrashed()
                ->with(['item', 'countries'])->where('item_id', 31)
                ->findOrFail($fiscalDocumentId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El documento fiscal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el documento fiscal: ' . $e->getMessage());
        }
    }

    public function store(FiscalDocument $fiscalDocument): FiscalDocument
    {
        try {
            $model = $this->fiscalDocumentModel
                ->create($this->mapToModelData($fiscalDocument));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el documento fiscal: ' . $e->getMessage());
        }
    }

    public function update(FiscalDocument $fiscalDocument, FiscalDocumentId $fiscalDocumentId): FiscalDocument
    {
        try {
            $model = tap($this->fiscalDocumentModel->where('item_id', 31)->whereKey($fiscalDocumentId->value())->firstOrFail())
                ->update($this->mapToModelData($fiscalDocument))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El documento fiscal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el documento fiscal: ' . $e->getMessage());
        }
    }

    public function toggle(FiscalDocument $fiscalDocument, FiscalDocumentId $fiscalDocumentId): FiscalDocument
    {
        try {
            $model = $this->fiscalDocumentModel->where('item_id', 31)->whereKey($fiscalDocumentId->value())->firstOrFail();

            $model->countries()->toggle(array_filter([
                $fiscalDocument->countryId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El documento fiscal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el documento fiscal: ' . $e->getMessage());
        }
    }

    public function delete(FiscalDocumentId $fiscalDocumentId): FiscalDocument
    {
        try {
            $model = $this->fiscalDocumentModel->where('item_id', 31)->whereKey($fiscalDocumentId->value())->firstOrFail();

            $isUsed = FiscalDocumentCertificate::where('fiscal_document_id', $fiscalDocumentId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El documento fiscal no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el documento fiscal: ' . $e->getMessage());
        }
    }

    public function restore(FiscalDocumentId $fiscalDocumentId): FiscalDocument
    {
        try {
            $model = tap($this->fiscalDocumentModel->withTrashed()->where('item_id', 31)->whereKey($fiscalDocumentId->value())->firstOrFail(), fn($fiscalDocument) => $fiscalDocument->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El documento fiscal no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el documento fiscal: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el documento fiscal: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): FiscalDocument
    {
        return new FiscalDocument(
            new FiscalDocumentId($model->id),
            new FiscalDocumentItemId($model->item_id),
            new FiscalDocumentName($model->name),
            new FiscalDocumentDescription($model->description),
            new FiscalDocumentSettings($model->settings),
            new FiscalDocumentCountryId(),
            new FiscalDocumentCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FiscalDocumentUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FiscalDocumentDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new FiscalDocumentRelationships($model->countries)
        );
    }

    private function mapToModelData(FiscalDocument $fiscalDocument): array
    {
        $array = [];

        if ($fiscalDocument->itemId()->value()) {
            $array['item_id'] = $fiscalDocument->itemId()->value();
        }

        if ($fiscalDocument->name()->value()) {
            $array['name'] = $fiscalDocument->name()->value();
        }

        if ($fiscalDocument->description()->value()) {
            $array['description'] = $fiscalDocument->description()->value();
        }

        if ($fiscalDocument->settings()->value()) {
            $array['settings'] = $fiscalDocument->settings()->value();
        }

        return $array;
    }
}
