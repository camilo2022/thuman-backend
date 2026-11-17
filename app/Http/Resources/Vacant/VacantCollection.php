<?php

namespace App\Http\Resources\Vacant;

use App\Models\Vacant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VacantCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'vacants' => $this->collection->map(function ($vacant) {
                return [
                    'id' => $vacant->id,
                    'uuid' => $vacant->uuid,
                    'hiring_reason' => $vacant->hiring_reason,
                    'position' => $vacant->position,
                    'person' => $vacant->person,
                    'headquarter' => $vacant->headquarter,
                    'contract_type' => $vacant->contract_type,
                    'work_arrangement' => $vacant->work_arrangement,
                    'work_day' => $vacant->work_day,
                    'salary_min' => $vacant->salary_min,
                    'salary_max' => $vacant->salary_max,
                    'start_date' => $this->formatDate($vacant->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($vacant->end_date, 'Y-m-d'),
                    'description' => $vacant->description,
                    'quantity' => $vacant->quantity,
                    'status' => $vacant->status,
                    'settings' => $vacant->settings,
                    'benefits' => $vacant->benefits,
                    'applications' => $vacant->applications,
                    'created_at' => $this->formatDate($vacant->created_at),
                    'updated_at' => $this->formatDate($vacant->updated_at)
                ];
            }),
            'filters' => $this->generateFilters(
                Vacant::where('status', Vacant::PUBLISHED)
                    ->when(
                        $request->filled('entity_id'),
                        fn($q) => $q->whereHas(
                            'headquarter.entity',
                            fn($sub) => $sub->where('id', $request->entity_id)
                        )
                    )
                    ->get(),
                $request
            ),
            'statuses' => Vacant::statuses(),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return $date ? Carbon::parse($date)->format($format) : $date;
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }

    protected function generateFilters($vacants, Request $request)
    {
        $experience = [
            'Sin experiencia' => 0,
            '1-3 años' => 0,
            '4-7 años' => 0,
            '8-10 años' => 0,
            'Más de 10 años' => 0,
        ];

        foreach ($vacants as $vacant) {
            $avgMonths = 0;
            $experiences = $vacant->position?->required_experiences ?? collect();

            if ($experiences->count() > 0) {
                $avgMonths = $experiences->sum('duration_months') / $experiences->count();
            }

            $years = $avgMonths / 12;

            if ($years == 0) $experience['Sin experiencia']++;
            elseif ($years <= 3) $experience['1-3 años']++;
            elseif ($years <= 7) $experience['4-7 años']++;
            elseif ($years <= 10) $experience['8-10 años']++;
            else $experience['Más de 10 años']++;
        }

        $workArrangements = $vacants
            ->groupBy(fn($v) => $v->work_arrangement->name ?? 'No definido')
            ->map(fn($group) => $group->count())
            ->toArray();

        $enterprises = $vacants
            ->groupBy(fn($v) => $v->headquarter->entity->enterprise->name ?? 'Sin empresa')
            ->map(fn($group) => $group->count())
            ->toArray();

        $contractTypes = $vacants
            ->groupBy(fn($v) => $v->contract_type->name ?? 'No definido')
            ->map(fn($group) => $group->count())
            ->toArray();

        $salaryRanges = [
            'Menos de 1.000.000' => 0,
            '1.000.000 - 2.000.000' => 0,
            '2.000.000 - 5.000.000' => 0,
            '5.000.000 - 10.000.000' => 0,
            'Más de 10.000.000' => 0,
        ];

        foreach ($vacants as $vacant) {
            $min = (int) ($vacant->salary_min ?? 0);
            $max = (int) ($vacant->salary_max ?? 0);

            // por seguridad, si vienen invertidos
            if ($min > $max) {
                [$min, $max] = [$max, $min];
            }

            if ($min < 1000000) {
                $salaryRanges['Menos de 1.000.000']++;
            }

            if ($max >= 1000000 && $min <= 1999999) {
                $salaryRanges['1.000.000 - 2.000.000']++;
            }

            if ($max >= 2000000 && $min <= 4999999) {
                $salaryRanges['2.000.000 - 5.000.000']++;
            }

            if ($max >= 5000000 && $min <= 9999999) {
                $salaryRanges['5.000.000 - 10.000.000']++;
            }

            if ($max > 10000000) {
                $salaryRanges['Más de 10.000.000']++;
            }
        }

        $locations = [];

        foreach ($vacants as $vacant) {
            $city = $vacant->headquarter?->city;
            $department = $city?->departament;
            $country = $department?->country;

            if (!$country || !$department || !$city) {
                continue;
            }

            if (!isset($locations[$country->name])) {
                $locations[$country->name] = [
                    'label' => $country->name,
                    'count' => 0,
                    'departments' => [],
                ];
            }

            $locations[$country->name]['count']++;

            if (!isset($locations[$country->name]['departments'][$department->name])) {
                $locations[$country->name]['departments'][$department->name] = [
                    'label' => $department->name,
                    'count' => 0,
                    'cities' => [],
                ];
            }

            $locations[$country->name]['departments'][$department->name]['count']++;

            if (!isset($locations[$country->name]['departments'][$department->name]['cities'][$city->name])) {
                $locations[$country->name]['departments'][$department->name]['cities'][$city->name] = [
                    'label' => $city->name,
                    'count' => 0,
                ];
            }

            $locations[$country->name]['departments'][$department->name]['cities'][$city->name]['count']++;
        }

        $locationOptions = collect($locations)->map(function ($country) {
            return [
                'label' => $country['label'],
                'count' => $country['count'],
                'options' => collect($country['departments'])->map(function ($dept) {
                    return [
                        'label' => $dept['label'],
                        'count' => $dept['count'],
                        'options' => collect($dept['cities'])->map(function ($city) {
                            return [
                                'label' => $city['label'],
                                'count' => $city['count'],
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        })->values();

        $hasEntityContext = $request->filled('entityId') || $request->filled('entity_id');

        $filters = [
            'experience' => ['label' => 'Experiencia', 'options' => collect($experience)->map(fn($v, $k) => ['label' => $k, 'count' => $v])->values()],
            'work_arrangement' => ['label' => 'Modalidad', 'options' => collect($workArrangements)->map(fn($v, $k) => ['label' => $k, 'count' => $v])->values()],
            'contract_type' => ['label' => 'Tipo de contrato', 'options' => collect($contractTypes)->map(fn($v, $k) => ['label' => $k, 'count' => $v])->values()],
            'salary' => ['label' => 'Salario', 'options' => collect($salaryRanges)->map(fn($v, $k) => ['label' => $k, 'count' => $v])->values()],
            'ubication' => ['label' => 'Ubicación', 'options' => $locationOptions],
        ];

        if (!$hasEntityContext) {
            $filters['enterprise'] = ['label' => 'Empresa', 'options' => collect($enterprises)->map(fn($v, $k) => ['label' => $k, 'count' => $v])->values()];
        }

        return $filters;
    }
}
