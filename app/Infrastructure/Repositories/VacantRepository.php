<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Contracts\VacantContract;
use App\Models\Vacant as VacantModel;
use App\Domain\Vacant\Vacant;
use App\Domain\Vacant\Values\VacantBenefitsId;
use App\Domain\Vacant\Values\VacantContractTypeId;
use App\Domain\Vacant\Values\VacantCreatedAt;
use App\Domain\Vacant\Values\VacantId;
use App\Domain\Vacant\Values\VacantDescription;
use App\Domain\Vacant\Values\VacantEndDate;
use App\Domain\Vacant\Values\VacantHeadquarterId;
use App\Domain\Vacant\Values\VacantHiringReasonId;
use App\Domain\Vacant\Values\VacantPersonId;
use App\Domain\Vacant\Values\VacantPositionId;
use App\Domain\Vacant\Values\VacantQuantity;
use App\Domain\Vacant\Values\VacantRelationships;
use App\Domain\Vacant\Values\VacantSalaryMax;
use App\Domain\Vacant\Values\VacantSalaryMin;
use App\Domain\Vacant\Values\VacantSettings;
use App\Domain\Vacant\Values\VacantStartDate;
use App\Domain\Vacant\Values\VacantStatus;
use App\Domain\Vacant\Values\VacantUpdatedAt;
use App\Domain\Vacant\Values\VacantUuid;
use App\Domain\Vacant\Values\VacantWorkArrangementId;
use App\Domain\Vacant\Values\VacantWorkDayId;
use App\Http\Requests\Vacant\VacantAllRequest;
use App\Http\Requests\Vacant\VacantFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class VacantRepository implements VacantContract
{
    private $vacantModel;

    public function __construct()
    {
        $this->vacantModel = new VacantModel;
    }

    public function all(string|null $search = null, int|null $perPage, VacantAllRequest $request): LengthAwarePaginator
    {
        try {
            $vacants = $this->vacantModel
                ->with([
                    'hiring_reason', 'position.required_experiences', 'person.employee', 'position.area', 'position.profile_category', 'headquarter.city.departament.country', 'contract_type',
                    'work_arrangement', 'work_day', 'benefits.icon', 'headquarter.entity.enterprise', 'headquarter.entity.entity_type', 'headquarter.entity.country', 'applications'
                ])
                ->when($search, fn ($query) => $query->search($search))
                ->filters($request)
                ->whereHas('headquarter')
                ->whereHas('headquarter.entity')
                ->whereHas('headquarter.entity.enterprise')
                ->when($request->filled('entity_id'), function ($q) use ($request) {
                    $q->whereHas('headquarter', function ($sq) use ($request) {
                        $sq->where('entity_id', $request->integer('entity_id'));
                    });
                })
                ->when($request->filled('area_id'), function ($q) use ($request) {
                    $q->whereHas('position', function ($sq) use ($request) {
                        $sq->where('area_id', $request->integer('area_id'));
                    });
                })
                ->when($request->filled('person_id'), function ($q) use ($request) {
                    $q->whereHas('people', function ($sq) use ($request) {
                        $sq->where('id', $request->integer('person_id'));
                    });
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->orderBy('id', 'asc');

            return $vacants->paginate($perPage ?? $vacants->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las vacantes: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las vacantes: ' . $e->getMessage());
        }
    }

    public function find(VacantFindRequest $request, VacantId $vacantId): ?Vacant
    {
        try {
            $model = $this->vacantModel
                ->with([
                    'hiring_reason', 'position.required_experiences', 'person.employee', 'position.area', 'position.profile_category', 'headquarter.city.departament.country', 'contract_type',
                    'work_arrangement', 'work_day', 'benefits.icon', 'headquarter.entity.enterprise', 'headquarter.entity.entity_type', 'headquarter.entity.country'
                ])
                ->where($vacantId->value() ? 'id' : 'uuid', $vacantId->value() ?? $request->input('vacant_uuid'))
                /*->when($vacantId->value(), function ($query) use ($vacantId) {
                    $query->where('id', $vacantId->value());
                })
                ->when(!$vacantId->value(), function ($query) use ($request) {
                    $query->where('uuid', $request->input('vacant_uuid'));
                })*/
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La vacante no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la vacante: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la vacante: ' . $e->getMessage());
        }
    }

    public function store(Vacant $vacant): Vacant
    {
        try {
            $model = $this->vacantModel
                ->create($this->mapToModelData($vacant));

            $model->benefits()->sync(array_filter(
                $vacant->benefitsId()->value()
            ));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la vacante: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la vacante: ' . $e->getMessage());
        }
    }

    public function update(Vacant $vacant, VacantId $vacantId): Vacant
    {
        try {
            $model = tap($this->vacantModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            $model->status == VacantModel::REVIEW
                ? $model->benefits()->sync(array_filter($vacant->benefitsId()->value()))
                : null;

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La vacante no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la vacante: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la vacante: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Vacant
    {
        return new Vacant(
            new VacantId($model->id),
            new VacantUuid($model->uuid),
            new VacantHiringReasonId($model->hiring_reason?->id ?? $model->hiring_reason_id),
            new VacantPositionId($model->position?->id ?? $model->position_id),
            new VacantPersonId($model->person?->id ?? $model->person_id),
            new VacantHeadquarterId($model->headquarter?->id ?? $model->headquarter_id),
            new VacantContractTypeId($model->contract_type?->id ?? $model->contract_type_id),
            new VacantWorkArrangementId($model->work_arrangement?->id ?? $model->work_arrangement_id),
            new VacantWorkDayId($model->work_day?->id ?? $model->work_day_id),
            new VacantSalaryMin($model->salary_min),
            new VacantSalaryMax($model->salary_max),
            new VacantStartDate($model->start_date?->format('d/m/Y')),
            new VacantEndDate($model->end_date?->format('d/m/Y')),
            new VacantDescription($model->description),
            new VacantQuantity($model->quantity),
            new VacantStatus($model->status),
            new VacantSettings($model->settings),
            new VacantBenefitsId($model->benefits->modelKeys()),
            new VacantCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new VacantUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new VacantRelationships($model->hiring_reason, $model->position, $model->person, $model->headquarter, $model->contract_type, $model->work_arrangement, $model->work_day, $model->benefits)
        );
    }

    private function mapToModelData(Vacant $vacant): array
    {
        $array = [];

        if ($vacant->hiringReasonId()->value()) {
            $array['hiring_reason_id'] = $vacant->hiringReasonId()->value();
        }

        if ($vacant->positionId()->value()) {
            $array['position_id'] = $vacant->positionId()->value();
        }

        if ($vacant->personId()->value()) {
            $array['person_id'] = $vacant->personId()->value();
        }

        if ($vacant->headquarterId()->value()) {
            $array['headquarter_id'] = $vacant->headquarterId()->value();
        }

        if ($vacant->contractTypeId()->value()) {
            $array['contract_type_id'] = $vacant->contractTypeId()->value();
        }

        if ($vacant->workArrangementId()->value()) {
            $array['work_arrangement_id'] = $vacant->workArrangementId()->value();
        }

        if ($vacant->workDayId()->value()) {
            $array['work_day_id'] = $vacant->workDayId()->value();
        }

        if ($vacant->salaryMin()->value()) {
            $array['salary_min'] = $vacant->salaryMin()->value();
        }

        if ($vacant->salaryMax()->value()) {
            $array['salary_max'] = $vacant->salaryMax()->value();
        }

        if ($vacant->startDate()->value()) {
            $array['start_date'] = $vacant->startDate()->value();
        }

        if ($vacant->endDate()->value()) {
            $array['end_date'] = $vacant->endDate()->value();
        }

        if ($vacant->description()->value()) {
            $array['description'] = $vacant->description()->value();
        }

        if ($vacant->quantity()->value()) {
            $array['quantity'] = $vacant->quantity()->value();
        }

        if ($vacant->status()->value()) {
            $array['status'] = $vacant->status()->value();
        }

        if ($vacant->settings()->value()) {
            $array['settings'] = $vacant->settings()->value();
        }

        return $array;
    }
}
