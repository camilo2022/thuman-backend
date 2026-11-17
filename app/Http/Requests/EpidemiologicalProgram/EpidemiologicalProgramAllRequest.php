<?php

namespace App\Http\Requests\EpidemiologicalProgram;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EpidemiologicalProgramAllRequest extends FormRequest
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
            'with_trashed' => filter_var($this->input('with_trashed'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function rules()
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'perPage' => ['nullable', 'numeric'],
            'with_trashed' => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'boolean' => 'Deber ser requerido o no requerido.',
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página',
            'with_trashed' => 'Registros inactivos',
        ];
    }
}
