<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Severance\Contracts\SeveranceContract;
use App\Models\Severance as SeveranceModel;
use App\Models\File as FileModel;
use App\Domain\Severance\Severance;
use App\Domain\Severance\File;
use App\Domain\Severance\Values\SeveranceSeveranceTypeId;
use App\Domain\Severance\Values\SeveranceCreatedAt;
use App\Domain\Severance\Values\SeveranceId;
use App\Domain\Severance\Values\SeverancePersonId;
use App\Domain\Severance\Values\SeveranceReason;
use App\Domain\Severance\Values\SeveranceRelationships;
use App\Domain\Severance\Values\SeveranceStatus;
use App\Domain\Severance\Values\SeveranceUpdatedAt;
use App\Domain\Severance\Values\FileCreatedAt;
use App\Domain\Severance\Values\FileDeletedAt;
use App\Domain\Severance\Values\FileExtension;
use App\Domain\Severance\Values\FileFileSubtypeId;
use App\Domain\Severance\Values\FileFileTypeId;
use App\Domain\Severance\Values\FileId;
use App\Domain\Severance\Values\FileMetadata;
use App\Domain\Severance\Values\FileMime;
use App\Domain\Severance\Values\FileModelId;
use App\Domain\Severance\Values\FileModelType;
use App\Domain\Severance\Values\FileName;
use App\Domain\Severance\Values\FilePath;
use App\Domain\Severance\Values\FileSize;
use App\Domain\Severance\Values\FileUpdatedAt;
use App\Http\Requests\Severance\SeveranceAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SeveranceRepository implements SeveranceContract
{
    private $severanceModel;
    private $fileModel;

    public function __construct()
    {
        $this->severanceModel = new SeveranceModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, SeveranceAllRequest $request): LengthAwarePaginator
    {
        try {
            $severances = $this->severanceModel
                ->with([
                    'severance_type', 'file', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('person_id'), function ($q) use ($request) {
                    $q->where('person_id', $request->integer('person_id'));
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->when($request->filled('area_id'), function ($q) use ($request) {
                    $q->whereHas('person.employee.position', function ($sq) use ($request) {
                        $sq->where('area_id', $request->integer('area_id'));
                    });
                })
                ->orderBy('id', 'asc');

            return $severances->paginate($perPage ?? $severances->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las solicitudes de retiro de las cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las solicitudes de retiro de las cesantías: ' . $e->getMessage());
        }
    }

    public function find(SeveranceId $severanceId): ?Severance
    {
        try {
            $model = $this->severanceModel
                ->with([
                    'severance_type', 'file', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->findOrFail($severanceId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de retiro de las cesantías no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar La solicitud de retiro de las cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar La solicitud de retiro de las cesantías: ' . $e->getMessage());
        }
    }

    public function store(Severance $severance): Severance
    {
        try {
            $model = $this->severanceModel
                ->create($this->mapToModelData($severance));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar La solicitud de retiro de las cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar La solicitud de retiro de las cesantías: ' . $e->getMessage());
        }
    }

    public function update(Severance $severance, SeveranceId $severanceId): Severance
    {
        try {
            $model = tap($this->severanceModel->findOrFail($severanceId->value()))
                ->update($this->mapToModelData($severance))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de retiro de las cesantías no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar La solicitud de retiro de las cesantías: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar La solicitud de retiro de las cesantías: ' . $e->getMessage());
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

    private function mapToEntity($model): Severance
    {
        return new Severance(
            new SeveranceId($model->id),
            new SeverancePersonId($model->person?->id ?? $model->person_id),
            new SeveranceSeveranceTypeId($model->severance_type?->id ?? $model->severance_type_id),
            new SeveranceReason($model->reason),
            new SeveranceStatus($model->status),
            new SeveranceCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new SeveranceUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new SeveranceRelationships($model->person, $model->severance_type, $model->file)
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

    private function mapToModelData(Severance $severance): array
    {
        $array = [];

        if ($severance->personId()->value()) {
            $array['person_id'] = $severance->personId()->value();
        }

        if ($severance->severanceTypeId()->value()) {
            $array['severance_type_id'] = $severance->severanceTypeId()->value();
        }

        if ($severance->reason()->value()) {
            $array['reason'] = $severance->reason()->value();
        }

        if ($severance->status()->value()) {
            $array['status'] = $severance->status()->value();
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
            $array['keys']['file_type_id'] = $file->fileTypeId()->value();
        }

        if ($file->fileSubtypeId()->value()) {
            $array['keys']['file_subtype_id'] = $file->fileSubtypeId()->value();
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
