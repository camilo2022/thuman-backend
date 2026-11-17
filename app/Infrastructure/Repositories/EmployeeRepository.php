<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Employee\Contracts\EmployeeContract;
use App\Models\Employee as EmployeeModel;
use App\Models\HealthEntityAffiliation as HealthEntityAffiliationModel;
use App\Models\PensionFundAffiliation as PensionFundAffiliationModel;
use App\Models\RiskManagerAffiliation as RiskManagerAffiliationModel;
use App\Models\BankCertificate as BankCertificateModel;
use App\Models\CompensationFundAffiliation as CompensationFundAffiliationModel;
use App\Models\FiscalDocumentCertificate as FiscalDocumentCertificateModel;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Values\EmployeeCampaignId;
use App\Domain\Employee\Values\EmployeeCreatedAt;
use App\Domain\Employee\Values\EmployeeDeletedAt;
use App\Domain\Employee\Values\EmployeeEndDate;
use App\Domain\Employee\Values\EmployeeHeadquarterId;
use App\Domain\Employee\Values\EmployeeId;
use App\Domain\Employee\Values\EmployeePersonId;
use App\Domain\Employee\Values\EmployeePositionId;
use App\Domain\Employee\Values\EmployeeStartDate;
use App\Domain\Employee\Values\EmployeeUpdatedAt;
use App\Domain\Employee\Values\EmployeeRelationships;
use App\Domain\Employee\Values\EmployeeSettings;
use App\Http\Requests\Employee\EmployeeAllRequest;
use App\Http\Requests\Employee\EmployeeFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EmployeeRepository implements EmployeeContract
{
    private $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel;
    }

    public function all(string|null $search = null, int|null $perPage, EmployeeAllRequest $request): LengthAwarePaginator
    {
        try {
            $employees = $this->employeeModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with([
                    'headquarter.entity.enterprise',
                    'headquarter.city',
                    'position.area',
                    'person.termination_review',
                    'person.contract.contract_type',
                    'person.birth_city.departament.country',
                    'person.document_issue_city.departament.country',
                    'person.city.departament.country',
                    'person.dialing_country',
                    'person.document_type',
                    'person.sex',
                    'person.gender',
                    'person.blood_type',
                    'person.marital_status',
                    'person.file.file_type',
                    'person.file.file_subtype',
                    'person.photo.file_type',
                    'person.photo.file_subtype',
                    'person.signature.file_type',
                    'person.signature.file_subtype',
                    'campaign',
                ])
                ->when($request->filled('person_id'), function ($q) use ($request) {
                    $q->whereHas('position', function ($q1) use ($request) {
                        $q1->whereHas('area', function ($q2) use ($request) {
                            $q2->whereIn('id', function ($sub) use ($request) {
                                $sub->select('areas.id')->from('employees')
                                    ->join('positions', 'positions.id', '=', 'employees.position_id')
                                    ->join('areas', 'areas.id', '=', 'positions.area_id')
                                    ->where('employees.person_id', $request->integer('person_id'));
                            });
                        });
                    })
                        ->whereIn('headquarter_id', function ($sub) use ($request) {
                            $sub->select('headquarter_id')
                                ->from('employees')
                                ->where('person_id', $request->integer('person_id'));
                        });
                })
                ->when($request->filled('position_id'), function ($q) use ($request) {
                    $q->where('position_id', $request->integer('position_id'));
                })
                ->when($request->boolean('terminations'), function ($q) use ($request) {
                    $q->whereHas('person.contract');
                })
                ->when($request->input('without_affiliations'), function ($q) {
                    $q->whereHas('person', function ($sq) {
                        $sq->whereDoesntHave('health_entity_affiliation')
                            ->orWhereDoesntHave('pension_fund_affiliation')
                            ->orWhereDoesntHave('risk_manager_affiliation')
                            ->orWhereDoesntHave('compensation_fund_affiliation')
                            ->orWhereDoesntHave('bank_certificate')
                            ->orWhereDoesntHave('fiscal_document_certificate')
                            ->orWhereHas(
                                'health_entity_affiliation',
                                fn($ssq) =>
                                $ssq->whereIn('status', [HealthEntityAffiliationModel::PENDING, HealthEntityAffiliationModel::REFRESHED])
                            )
                            ->orWhereHas(
                                'pension_fund_affiliation',
                                fn($ssq) =>
                                $ssq->whereIn('status', [PensionFundAffiliationModel::PENDING, PensionFundAffiliationModel::REFRESHED])
                            )
                            ->orWhereHas(
                                'risk_manager_affiliation',
                                fn($ssq) =>
                                $ssq->whereIn('status', [RiskManagerAffiliationModel::PENDING, RiskManagerAffiliationModel::REFRESHED])
                            )
                            ->orWhereHas(
                                'compensation_fund_affiliation',
                                fn($ssq) =>
                                $ssq->whereIn('status', [CompensationFundAffiliationModel::PENDING, CompensationFundAffiliationModel::REFRESHED])
                            )
                            ->orWhereHas(
                                'bank_certificate',
                                fn($ssq) =>
                                $ssq->whereIn('status', [BankCertificateModel::PENDING, BankCertificateModel::REFRESHED])
                            )
                            ->orWhereHas(
                                'fiscal_document_certificate',
                                fn($ssq) =>
                                $ssq->whereIn('status', [FiscalDocumentCertificateModel::PENDING, FiscalDocumentCertificateModel::REFRESHED])
                            );
                    });
                })
                ->when($request->filled('area_id'), function ($q) use ($request) {
                    $q->whereHas('position', fn($q1) => $q1->where('area_id', $request->integer('area_id')));
                })
                ->when($request->filled('contract_type_id'), function ($q) use ($request) {
                    $q->whereHas('person.contract', fn($q1) => $q1->where('contract_type_id', $request->integer('contract_type_id')));
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $status = $request->input('status');
                    $q->whereHas('person.contract', function ($sq) use ($status) {
                        if ($status == 'Vigente') {
                            $sq->where(function ($ssq) {
                                $ssq->whereNull('end_date')
                                    ->orWhere('end_date', '>', now()->addDays(30)->toDateString());
                            });
                        }
                        if ($status === 'Próximo a vencer') {
                            $sq->whereNotNull('end_date')
                                ->whereBetween('end_date', [
                                    now()->toDateString(),
                                    now()->addDays(30)->toDateString()
                                ]);
                        }

                        if ($status === 'Vencido') {
                            $sq->whereNotNull('end_date')
                                ->where('end_date', '<', now()->toDateString());
                        }
                    });
                })
                ->when($request->filled('entity_id'), function ($q) use ($request) {
                    $q->whereHas('headquarter', function ($q1) use ($request) {
                        $q1->where('entity_id', $request->integer('entity_id'));
                    });
                })
                ->when($request->filled('headquarter_id'), function ($q) use ($request) {
                    $q->whereHas('headquarter', function ($q1) use ($request) {
                        $q1->where('id', $request->integer('headquarter_id'));
                    });
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $employees->paginate($perPage ?? $employees->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los empleados:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los empleados:' . $e->getMessage());
        }
    }

    public function find(EmployeeFindRequest $request, EmployeeId $employeeId): ?Employee
    {
        try {
            $model = $this->employeeModel
                ->with([
                    'headquarter.entity.enterprise',
                    'headquarter.city',
                    'position.area',
                    'person.termination_review',
                    'person.contract.contract_type',
                    'person.birth_city.departament.country',
                    'person.document_issue_city.departament.country',
                    'person.city.departament.country',
                    'person.dialing_country',
                    'person.document_type',
                    'person.sex',
                    'person.gender',
                    'person.blood_type',
                    'person.marital_status',
                    'person.file.file_type',
                    'person.file.file_subtype',
                    'person.photo.file_type',
                    'person.photo.file_subtype',
                    'person.signature.file_type',
                    'person.signature.file_subtype'
                ])
                ->when($employeeId->value(), function ($query) use ($employeeId) {
                    $query->where('id', $employeeId->value());
                })
                ->when(!$employeeId->value(), function ($query) use ($request) {
                    $query->when($request->filled('person_id'), function ($query) use ($request) {
                        $query->where('person_id', $request->input('person_id'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El empleado no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el empleado:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el empleado:' . $e->getMessage());
        }
    }

    public function update(Employee $employee, EmployeeId $employeeId): Employee
    {
        try {
            $model = tap($this->employeeModel->findOrFail($employeeId->value()))
                ->update($this->mapToModelData($employee))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El empleado no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el empleado: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el empleado: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Employee
    {
        return new Employee(
            new EmployeeId($model->id),
            new EmployeePersonId($model->person?->id ?? $model->person_id),
            new EmployeeHeadquarterId($model->headquarter?->id ?? $model->headquarter_id),
            new EmployeePositionId($model->position?->id ?? $model->position_id),
            new EmployeeCampaignId($model->campaing?->id ?? $model->campaing_id),
            new EmployeeStartDate($model->start_date?->format('Y-m-d')),
            new EmployeeEndDate($model->end_date?->format('Y-m-d')),
            new EmployeeSettings($model->settings),
            new EmployeeCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new EmployeeUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new EmployeeDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new EmployeeRelationships($model->person, $model->headquarter, $model->position, $model->campaing)
        );
    }

    private function mapToModelData(Employee $employee): array
    {
        $array = [];

        /*if ($employee->personId()->value()) {
            $array['person_id'] = $employee->personId()->value();
        }

        if ($employee->headquarterId()->value()) {
            $array['headquarter_id'] = $employee->headquarterId()->value();
        }

        if ($employee->positionId()->value()) {
            $array['position_id'] = $employee->positionId()->value();
        }

        if ($employee->startDate()->value()) {
            $array['start_date'] = $employee->startDate()->value();
        }

        if ($employee->endDate()->value()) {
            $array['end_date'] = $employee->endDate()->value();
        }*/

        if ($employee->settings()->endowment()) {
            $array['settings']['endowment'] = $employee->settings()->endowment();
        }

        return $array;
    }
}
