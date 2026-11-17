<?php

namespace App\Http\Requests\Vacant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VacantUpdateRequest extends FormRequest
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
        return [
            'hiring_reason_id' => ['required', 'exists:subitems,id,item_id,33'],
            'position_id' => ['required', 'exists:positions,id'],
            'person_id' => [in_array($this->input('hiring_reason_id'), [350,351]) ? 'required' : 'nullable', 'exists:people,id', 'exists:employees,person_id,deleted_at,NULL'],
            'headquarter_id' => ['required', 'exists:headquarters,id'],
            'contract_type_id' => ['required', 'exists:subitems,id,item_id,1'],
            'work_arrangement_id' => ['required', 'exists:subitems,id,item_id,3'],
            'work_day_id' => ['required', 'exists:subitems,id,item_id,2'],
            'salary_min' => ['required', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'gte:salary_min'],
            'start_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'end_date'   => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string', 'min:10', 'max:2000'],
            'quantity' => ['required', 'integer', 'min:1'],
            'settings' => ['required', 'array'],
            'settings.polygraph' => ['required', 'boolean'],
            'settings.video_interview' => ['required', 'boolean'],
            'benefits_id' => ['required', 'array'],
            'benefits_id.*' => ['required', 'exists:subitems,id,item_id,19'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'integer' => 'Debe ser un valor numérico entero.',
            'min' => 'Debe ser mínimo :min.',
            'gte' => 'Debe ser mayor o igual que :value.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.',
            'array' => 'Debe ser una lista de datos.',
            'boolean' => 'Deber ser requerido o no requerido.'
        ];
    }

    public function attributes()
    {
        return [
            'hiring_reason_id' => 'Motivo de contratación',
            'position_id' => 'Cargo',
            'person_id' => 'Persona',
            'headquarter_id' => 'Sede',
            'contract_type_id' => 'Tipo de contrato',
            'work_arrangement_id' => 'Modalidad de trabajo',
            'work_day_id' => 'Jornada laboral',
            'salary_min' => 'Salario mínimo',
            'salary_max' => 'Salario máximo',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'description' => 'Descripción',
            'quantity' => 'Cantidad de personal',
            'settings' => 'Configuración',
            'settings.polygraph' => 'Poligrafo',
            'settings.video_interview' => 'Video entrevista',
            'benefits_id' => 'Beneficios',
            'benefits_id.*' => 'Beneficio',
        ];
    }
}
