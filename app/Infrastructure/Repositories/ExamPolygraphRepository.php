<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\ExamPolygraph\Contracts\ExamPolygraphContract;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphApplicationId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphCreatedAt;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphRelationships;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphStatus;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphUpdatedAt;
use App\Domain\Vacant\Application\ExamPolygraph\ExamPolygraph;
use App\Domain\Vacant\Application\ExamPolygraph\File;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphAddress;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphCityId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphDateTime;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphDescription;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphServiceProviderId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphIndication;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphNeighborhood;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphResult;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileCreatedAt;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileDeletedAt;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileExtension;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileFileSubtypeId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileFileTypeId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileMetadata;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileMime;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileModelId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileModelType;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileName;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FilePath;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileSize;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileUpdatedAt;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphAllRequest;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphFindRequest;
use App\Models\Application as ApplicationModel;
use App\Models\ExamPolygraph as ExamPolygraphModel;
use App\Models\File as FileModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ExamPolygraphRepository implements ExamPolygraphContract
{
    private $examPolygraphModel;
    private $fileModel;

    public function __construct()
    {
        $this->examPolygraphModel = new ExamPolygraphModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, ExamPolygraphAllRequest $request): LengthAwarePaginator
    {
        try {
            $examPolygraphs = $this->examPolygraphModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'service_provider', 'file',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'city.departament.country'
                ])
                ->whereHas('application', fn($r) => $r->whereIn('status', [ApplicationModel::FINALIST]))
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->orderBy('id', 'asc');

            return $examPolygraphs->paginate($perPage ?? $examPolygraphs->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los registros de los examenes médicos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los registros de los examenes médicos: ' . $e->getMessage());
        }
    }

    public function find(ExamPolygraphFindRequest $request, ExamPolygraphId $examPolygraphId): ?ExamPolygraph
    {
        try {
            $model = $this->examPolygraphModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'service_provider', 'file',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'city.departament.country'
                ])
                ->when($examPolygraphId->value(), function ($query) use ($examPolygraphId) {
                    $query->where('id', $examPolygraphId->value());
                })
                ->when(!$examPolygraphId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de examen médico no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro de examen médico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro de examen médico: ' . $e->getMessage());
        }
    }

    public function store(ExamPolygraph $examPolygraph): ExamPolygraph
    {
        try {
            $model = $this->examPolygraphModel
                ->create($this->mapToModelData($examPolygraph));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro de examen médico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro de examen médico: ' . $e->getMessage());
        }
    }

    public function update(ExamPolygraph $vacant, ExamPolygraphId $vacantId): ExamPolygraph
    {
        try {
            $model = tap($this->examPolygraphModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de examen médico no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro de examen médico: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro de examen médico: ' . $e->getMessage());
        }
    }

    public function file(File $file): File
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataFile($file, 'keys'), $this->mapToModelDataFile($file, 'data'));

            return $this->mapToEntityFile($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El archivo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar el archivo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar el archivo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): ExamPolygraph
    {
        return new ExamPolygraph(
            new ExamPolygraphId($model->id),
            new ExamPolygraphApplicationId($model->application?->id ?? $model->application_id),
            new ExamPolygraphDateTime($model->date_time?->format('Y-m-d H:i:s')),
            new ExamPolygraphCityId($model->city_id),
            new ExamPolygraphServiceProviderId($model->service_provider_id),
            new ExamPolygraphAddress($model->address),
            new ExamPolygraphNeighborhood($model->neighborhood),
            new ExamPolygraphResult($model->result),
            new ExamPolygraphDescription($model->description),
            new ExamPolygraphIndication($model->indication),
            new ExamPolygraphStatus($model->status),
            new ExamPolygraphCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ExamPolygraphUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ExamPolygraphRelationships($model->application, $model->city, $model->service_provider, $model->file)
        );
    }

    private function mapToEntityFile($model): File
    {
        return new File(
            new FileId($model->id),
            new FileModelId($model->model?->id ?? $model->model_id),
            new FileModelType($model->model_type),
            new FileFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new FileFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new FileName($model->name),
            new FilePath($model->path),
            new FileMime($model->mime),
            new FileExtension($model->extension),
            new FileSize($model->size),
            new FileMetadata($model->metadata),
            new FileCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FileUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FileDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(ExamPolygraph $examPolygraph): array
    {
        $array = [];

        if ($examPolygraph->applicationId()->value()) {
            $array['application_id'] = $examPolygraph->applicationId()->value();
        }

        if ($examPolygraph->dateTime()->value()) {
            $array['date_time'] = $examPolygraph->dateTime()->value();
        }

        if ($examPolygraph->cityId()->value()) {
            $array['city_id'] = $examPolygraph->cityId()->value();
        }

        if ($examPolygraph->serviceProviderId()->value()) {
            $array['service_provider_id'] = $examPolygraph->serviceProviderId()->value();
        }

        if ($examPolygraph->address()->value()) {
            $array['address'] = $examPolygraph->address()->value();
        }

        if ($examPolygraph->neighborhood()->value()) {
            $array['neighborhood'] = $examPolygraph->neighborhood()->value();
        }

        if ($examPolygraph->result()->value()) {
            $array['result'] = $examPolygraph->result()->value();
        }

        if ($examPolygraph->description()->value()) {
            $array['description'] = $examPolygraph->description()->value();
        }

        if ($examPolygraph->indication()->value()) {
            $array['indication'] = $examPolygraph->indication()->value();
        }

        if ($examPolygraph->status()->value()) {
            $array['status'] = $examPolygraph->status()->value();
        }

        return $array;
    }

    private function mapToModelDataFile(File $file, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($file->modelId()->value()) {
            $array['keys']['model_id'] = $file->modelId()->value();
        }

        if ($file->modelType()->value()) {
            $array['keys']['model_type'] = $file->modelType()->value();
        }

        if ($file->fileTypeId()->value()) {
            $array['data']['file_type_id'] = $file->fileTypeId()->value();
        }

        if ($file->fileSubtypeId()->value()) {
            $array['data']['file_subtype_id'] = $file->fileSubtypeId()->value();
        }

        if ($file->name()->value()) {
            $array['data']['name'] = $file->name()->value();
        }

        if ($file->path()->value()) {
            $array['data']['path'] = $file->path()->value();
        }

        if ($file->mime()->value()) {
            $array['data']['mime'] = $file->mime()->value();
        }

        if ($file->extension()->value()) {
            $array['data']['extension'] = $file->extension()->value();
        }

        if ($file->size()->value()) {
            $array['data']['size'] = $file->size()->value();
        }

        if ($file->metadata()->value()) {
            $array['data']['metadata'] = $file->metadata()->value();
        }

        return $array[$index];
    }
}
