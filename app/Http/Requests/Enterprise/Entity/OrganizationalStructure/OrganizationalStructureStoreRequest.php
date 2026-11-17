<?php

namespace App\Http\Requests\Enterprise\Entity\OrganizationalStructure;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrganizationalStructureStoreRequest extends FormRequest
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
            'entity_id' => ['required', 'exists:entities,id'],

            'position_id' => [
                'required',
                'exists:positions,id',
                Rule::unique('organizational_structures', 'position_id')
                    ->where('entity_id', $this->input('entity_id'))
                    ->when(
                        $this->filled('parent_position_id'),
                        fn($query) => $query->where(
                            'parent_position_id',
                            $this->input('parent_position_id')
                        ),
                        fn($query) => $query->whereNull('parent_position_id')
                    )
                    ->whereIn('status', ['Pendiente', 'Aprobado'])
                    ->whereNull('deleted_at'),
            ],
            'parent_position_id' => [
                'nullable',
                'exists:positions,id',
                'different:position_id',
                function ($attribute, $value, $fail) {
                    if ($value === null) {
                        return;
                    }
                    $inverseExists = DB::table('organizational_structures')
                        ->where('entity_id', $this->input('entity_id'))
                        ->where('position_id', $value)
                        ->where('parent_position_id', $this->input('position_id'))
                        ->whereIn('status', ['Pendiente', 'Aprobado'])
                        ->whereNull('deleted_at')
                        ->exists();

                    if ($inverseExists) {
                        $fail('Ya existe la relación inversa configurada (pendiente o aprobada).');
                    }
                },
            ],
            'start_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:today'
            ],
            'end_date' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:start_date'
            ],
            'reason' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ]
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
            'min' => 'Debe ser mínimo :min caracteres.',
            'position_id.unique' =>
            'Esta relación entre cargos ya está configurada (pendiente o aprobada).',
            'parent_position_id.different' =>
            'El cargo superior no puede ser el mismo cargo.',
            'start_date.after_or_equal' =>
            'La fecha de inicio debe ser igual o posterior a hoy.',
            'end_date.after_or_equal' =>
            'La fecha de finalización debe ser igual o posterior a la fecha de inicio.',
        ];
    }
    public function attributes()
    {
        return [
            'entity_id' => 'Entidad internacional',
            'position_id' => 'Cargo',
            'parent_position_id' => 'Cargo superior',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de finalización',
            'reason' => 'Motivo'
        ];
    }
}
