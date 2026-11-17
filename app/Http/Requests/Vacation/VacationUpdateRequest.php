<?php

namespace App\Http\Requests\Vacation;

use App\Models\Person;
use App\Models\Vacation;
use App\Models\VacationType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Spatie\Holidays\Holidays;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VacationUpdateRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'attributes' => $this->attributes(),
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $person = Person::with('employee.headquarter.entity')->find($this->input('person_id'));
        $vacationType = VacationType::find($this->input('vacation_type_id'));

        return [
            'person_id' => ['required', 'exists:people,id', 'exists:employees,person_id,deleted_at,NULL'],
            'vacation_type_id' => ['required', 'exists:subitems,id,item_id,41'],
            'period' => ['required', 'integer', 'min:' . Carbon::parse($person?->contract?->start_date)->format('Y'), 'max:' . Carbon::now()->format('Y')],
            'days' => $vacationType?->settings?->field_type == 'numeric'
                ? ['required', 'integer',
                    'min:' . collect($vacationType?->settings?->validations ?? [])->where('country_id', $person?->employee?->headquarter?->entity?->country_id)->first()?->minimum_days,
                    'max:' . collect($person?->employee?->headquarter?->entity?->settings?->vacations ?? [])->where('period', $this->integer('period'))->first()?->legal_days]
                : ['nullable', 'in:NULL,0'],
            'start_date' => $vacationType?->settings?->field_type == 'date_range' ? ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'] : ['nullable', 'in:NULL'],
            'end_date' => $vacationType?->settings?->field_type == 'date_range' ? ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'] : ['nullable', 'in:NULL'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min.',
            'integer' => 'Debe ser un valor numérico entero.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'vacation_type_id' => 'Tipo de vacaciones',
            'period' => 'Periodo',
            'days' => 'Días',
            'start_date' => 'Fecha inicio',
            'end_date' => 'Fecha fin'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $person = Person::with('employee.headquarter.entity.country')->find($this->input('person_id'));
            if (!$person) return;

            $entity = $person->employee?->headquarter?->entity;
            if (!$entity) return;

            $period = (int) $this->input('period');
            $vacationSettings = collect($entity?->settings?->vacations ?? [])->where('period', $this->input('period'))->first();

            if (!$vacationSettings) {
                $validator->errors()->add('period', 'No existe configuración de vacaciones para el año ' . $this->input('period') . '.');
                return;
            }

            $holidays = collect(Holidays::for($entity->country?->iso2 ?? 'CO')->get())->pluck('date')
                ->map(fn ($date) => Carbon::parse($date)->toDateString())
                ->toArray();

            $start = Carbon::parse($this->input('start_date'));
            $end = Carbon::parse($this->input('end_date'));

            $requestedBusinessDays = 0;

            foreach (CarbonPeriod::create($start, $end) as $date) {
                if (in_array($date->isoWeekday(), $vacationSettings?->days ?? [], true) && !in_array($date->toDateString(), $holidays, true)) $requestedBusinessDays++;
            }

            $usedDays = Vacation::where('person_id', $person->id)->where('period', $period)->where('id', '<>', $this->route('vacationId'))
                ->whereIn('status', [Vacation::REVIEW, Vacation::APPROVED])->with('vacation_type')->get()
                ->sum(function ($vacation) use ($vacationSettings, $holidays) {
                    if ($vacation->vacation_type?->settings?->field_type === 'numeric') {
                        return (int) ($vacation->days ?? 0);
                    }
                    if ($vacation->vacation_type?->settings?->field_type === 'date_range') {
                        if (!$vacation->start_date || !$vacation->end_date) return 0;

                        $count = 0;
                        foreach (CarbonPeriod::create($vacation->start_date, $vacation->end_date) as $date) {
                            if (in_array($date->isoWeekday(), $vacationSettings?->days ?? [], true) && !in_array($date->toDateString(), $holidays, true)) $count++;
                        }
                        return $count;
                    }
                    return 0;
                });

            if (($usedDays + $requestedBusinessDays) > ($vacationSettings?->legal_days ?? 0)) {
                $validator->errors()->add('vacation_type_id', 'Con esta solicitud excede el máximo de ' . ($vacationSettings?->legal_days ?? 0) . ' días hábiles de vacaciones para el año ' . $this->input('period') . '.');
            }

            $vacationType = VacationType::find($this->input('vacation_type_id'));

            $currentValidation = collect($vacationType?->settings?->validations ?? [])
                ->firstWhere('country_id', $entity->country_id);

            if ($currentValidation && ($currentValidation->prerequisite ?? false)) {
                return;
            }

            $vacationTypes = VacationType::whereJsonContains('settings->validations', [['country_id' => $entity->country_id, 'prerequisite' => true, 'deleted_at' => null]])->get();

            foreach ($vacationTypes as $vacationType) {
                $exists = Vacation::where('person_id', $person->id)->where('vacation_type_id', $vacationType->id)->where('id', '<>', $this->route('vacationId'))
                    ->whereIn('status', [Vacation::REVIEW, Vacation::APPROVED])->exists();

                if (!$exists) {
                    $validator->errors()->add('vacation_type_id', 'Para solicitar este tipo de vacaciones, debe tener solicitudes prerequeridas en alguno de los siguientes estados: '. implode(', ', [Vacation::REVIEW, Vacation::APPROVED]) . '.');
                    break;
                }
            }
        });
    }
}
