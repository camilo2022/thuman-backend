<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Resignation\Contracts\ResignationContract;
use App\Models\Resignation as ResignationModel;
use App\Domain\Resignation\Resignation;
use App\Domain\Resignation\Values\ResignationResignationTypeId;
use App\Domain\Resignation\Values\ResignationCreatedAt;
use App\Domain\Resignation\Values\ResignationDate;
use App\Domain\Resignation\Values\ResignationId;
use App\Domain\Resignation\Values\ResignationPersonId;
use App\Domain\Resignation\Values\ResignationReason;
use App\Domain\Resignation\Values\ResignationRelationships;
use App\Domain\Resignation\Values\ResignationStatus;
use App\Domain\Resignation\Values\ResignationUpdatedAt;
use App\Http\Requests\Resignation\ResignationAllRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ResignationRepository implements ResignationContract
{
    private $resignationModel;

    public function __construct()
    {
        $this->resignationModel = new ResignationModel;
    }

    public function all(string|null $search = null, int|null $perPage, ResignationAllRequest $request): LengthAwarePaginator
    {
        try {
            $resignations = $this->resignationModel
                ->with([
                    'resignation_type', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
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

            return $resignations->paginate($perPage ?? $resignations->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las renuncias: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las renuncias: ' . $e->getMessage());
        }
    }

    public function find(ResignationId $resignationId): ?Resignation
    {
        try {
            $model = $this->resignationModel
                ->with([
                    'resignation_type', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->findOrFail($resignationId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La renuncia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la renuncia: ' . $e->getMessage());
        }
    }

    public function store(Resignation $resignation): Resignation
    {
        try {
            $model = $this->resignationModel
                ->create($this->mapToModelData($resignation));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la renuncia: ' . $e->getMessage());
        }
    }

    public function update(Resignation $resignation, ResignationId $resignationId): Resignation
    {
        try {
            $model = tap($this->resignationModel->findOrFail($resignationId->value()))
                ->update($this->mapToModelData($resignation))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La renuncia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la renuncia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la renuncia: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Resignation
    {
        return new Resignation(
            new ResignationId($model->id),
            new ResignationPersonId($model->person?->id ?? $model->person_id),
            new ResignationResignationTypeId($model->resignation_type?->id ?? $model->resignation_type_id),
            new ResignationDate($model->date?->format('Y-m-d')),
            new ResignationReason($model->reason),
            new ResignationStatus($model->status),
            new ResignationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ResignationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ResignationRelationships($model->person, $model->resignation_type)
        );
    }

    private function mapToModelData(Resignation $resignation): array
    {
        $array = [];

        if ($resignation->personId()->value()) {
            $array['person_id'] = $resignation->personId()->value();
        }

        if ($resignation->resignationTypeId()->value()) {
            $array['resignation_type_id'] = $resignation->resignationTypeId()->value();
        }

        if ($resignation->date()->value()) {
            $array['date'] = $resignation->date()->value();
        }

        if ($resignation->reason()->value()) {
            $array['reason'] = $resignation->reason()->value();
        }

        if ($resignation->status()->value()) {
            $array['status'] = $resignation->status()->value();
        }

        return $array;
    }
}
