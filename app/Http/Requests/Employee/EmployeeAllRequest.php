<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeAllRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'with_trashed' => filter_var($this->input('with_trashed'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'without_affiliations' => filter_var($this->input('without_affiliations'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function rules()
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'perPage' => ['nullable', 'numeric'],
            'with_trashed' => ['nullable', 'boolean'],
            'person_id' => ['nullable', 'exists:people,id'],
            'position_id' => ['nullable', 'exists:positions,id'],
            'without_affiliations' => ['nullable', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.',
            'boolean' => 'Deber ser requerido o no requerido.'
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página',
            'with_trashed' => 'Registros inactivos',
            'person_id' => 'Persona',
            'position_id' => 'Perfil de cargo',
            'without_affiliations' => 'Sin afiliaciones cargadas'
        ];
    }
}
