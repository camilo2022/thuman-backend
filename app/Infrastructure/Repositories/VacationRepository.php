<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacation\Contracts\VacationContract;
use App\Models\Vacation as VacationModel;
use App\Models\Person as PersonModel;
use App\Domain\Vacation\Vacation;
use App\Domain\Vacation\Values\VacationVacationTypeId;
use App\Domain\Vacation\Values\VacationCreatedAt;
use App\Domain\Vacation\Values\VacationDays;
use App\Domain\Vacation\Values\VacationId;
use App\Domain\Vacation\Values\VacationEndDate;
use App\Domain\Vacation\Values\VacationPeriod;
use App\Domain\Vacation\Values\VacationPersonId;
use App\Domain\Vacation\Values\VacationRelationships;
use App\Domain\Vacation\Values\VacationStartDate;
use App\Domain\Vacation\Values\VacationStatus;
use App\Domain\Vacation\Values\VacationUpdatedAt;
use App\Http\Requests\Vacation\VacationAllRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Holidays\Holidays;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class VacationRepository implements VacationContract
{
    private $vacationModel;
    private $personModel;

    public function __construct()
    {
        $this->vacationModel = new VacationModel;
        $this->personModel = new PersonModel;
    }

    public function all(string|null $search = null, int|null $perPage, VacationAllRequest $request): LengthAwarePaginator
    {
        try {
            $vacations = $this->vacationModel
                ->with([
                    'vacation_type', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->when($search, fn($query) => $query->search($search))
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
                ->when($request->filled('vacation_type_id'), function ($q) use ($request) {
                    $q->where('vacation_type_id', $request->input('vacation_type_id'));
                })
                ->when($request->filled('period'), function ($q) use ($request) {
                    $q->where('period', $request->input('period'));
                })
                ->orderBy('id', 'asc');

            return $vacations->paginate($perPage ?? $vacations->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las solicitudes de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las solicitudes de vacaciones: ' . $e->getMessage());
        }
    }

    public function find(VacationId $vacationId): ?Vacation
    {
        try {
            $model = $this->vacationModel
                ->with([
                    'vacation_type', 'person.employee.position.area', 'person.contract.contract_type','person.birth_city.departament.country', 'person.document_issue_city.departament.country',
                    'person.city.departament.country', 'person.dialing_country', 'person.document_type', 'person.sex', 'person.gender', 'person.blood_type', 'person.marital_status',
                    'person.file.file_type', 'person.file.file_subtype', 'person.photo.file_type', 'person.photo.file_subtype', 'person.signature.file_type', 'person.signature.file_subtype'
                ])
                ->findOrFail($vacationId->value());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de vacaciones no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la solicitud de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la solicitud de vacaciones: ' . $e->getMessage());
        }
    }

    public function store(Vacation $vacation): Vacation
    {
        try {
            $model = $this->vacationModel
                ->create($this->mapToModelData($vacation));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la solicitud de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la solicitud de vacaciones: ' . $e->getMessage());
        }
    }

    public function update(Vacation $vacation, VacationId $vacationId): Vacation
    {
        try {
            $model = tap($this->vacationModel->findOrFail($vacationId->value()))
                ->update($this->mapToModelData($vacation))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de vacaciones no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la solicitud de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la solicitud de vacaciones: ' . $e->getMessage());
        }
    }

    public function availability(int $personId): object
    {
        try {
            $person = $this->personModel->with('contract.headquarter.entity')->findOrFail($personId);

            if (!$person->contract || !$person->contract->start_date) throw new HttpException(422, 'No puedes solicitar vacaciones si no tienes un contrato vigente.');

            $yearsWorked = $person->contract->start_date->diffInYears(Carbon::now());
            $periods = collect();

            for ($i = 0; $i < $yearsWorked; $i++) {
                $periods->push($person->contract->start_date->copy()->addYears($i)->year);
            }

            $entity = $person->contract->headquarter->entity;

            $vacationSettings = collect($entity?->settings?->vacations ?? []);
            $vacationSettingsByPeriod = $vacationSettings->keyBy('period');

            $countryIso = $entity?->country?->iso2 ?? 'CO';

            $holidays = collect(Holidays::for($countryIso)->get())->pluck('date')->map(fn($date) => Carbon::parse($date)->toDateString())->toArray();

            $existingVacations = $this->vacationModel->with('vacation_type')->where('person_id', $personId)->whereIn('status', [VacationModel::REVIEW, VacationModel::APPROVED])->get()->groupBy('period');

            return $periods->map(function ($period) use ($vacationSettingsByPeriod, $existingVacations, $holidays, $vacationSettings) {
                $settings = $vacationSettingsByPeriod[$period] ?? null;

                $legalDays = (int) ($settings->legal_days ?? 0);
                $businessDays = $settings->days ?? [];

                $usedDays = 0;

                foreach ($existingVacations[$period] ?? [] as $vacation) {

                    if ($vacation->vacation_type?->settings?->field_type === 'numeric') {
                        $usedDays += $vacation->days ?? 0;
                        continue;
                    }

                    if ($vacation->vacation_type?->settings?->field_type === 'date_range' && $vacation->start_date && $vacation->end_date) {
                        foreach (CarbonPeriod::create($vacation->start_date, $vacation->end_date) as $date) {
                            if (in_array($date->isoWeekday(), $businessDays, true) && !in_array($date->toDateString(), $holidays, true)) {
                                $usedDays++;
                            }
                        }
                    }
                }

                return [
                    'period' => $period,
                    'legal_days' => $legalDays,
                    'days' => $vacationSettings->where('period', $period)->first()?->days ?? [],
                    'available_days' => max(0, $legalDays - $usedDays),
                ];
            })->values();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La solicitud de vacaciones no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la solicitud de vacaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la solicitud de vacaciones: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Vacation
    {
        return new Vacation(
            new VacationId($model->id),
            new VacationPersonId($model->person?->id ?? $model->person_id),
            new VacationVacationTypeId($model->vacation_type?->id ?? $model->vacation_type_id),
            new VacationPeriod($model->period),
            new VacationDays($model->days),
            new VacationStartDate($model->start_date?->format('d/m/Y')),
            new VacationEndDate($model->end_date?->format('d/m/Y')),
            new VacationStatus($model->status),
            new VacationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new VacationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new VacationRelationships($model->person, $model->vacation_type)
        );
    }

    private function mapToModelData(Vacation $vacation): array
    {
        $array = [];

        if ($vacation->personId()->value()) {
            $array['person_id'] = $vacation->personId()->value();
        }

        if ($vacation->vacationTypeId()->value()) {
            $array['vacation_type_id'] = $vacation->vacationTypeId()->value();
        }

        if ($vacation->period()->value()) {
            $array['period'] = $vacation->period()->value();
        }

        if ($vacation->days()->value()) {
            $array['days'] = $vacation->days()->value();
        }

        if ($vacation->startDate()->value()) {
            $array['start_date'] = $vacation->startDate()->value();
        }

        if ($vacation->endDate()->value()) {
            $array['end_date'] = $vacation->endDate()->value();
        }

        if ($vacation->status()->value()) {
            $array['status'] = $vacation->status()->value();
        }

        return $array;
    }
}
