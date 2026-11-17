<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonToggleRequest extends FormRequest
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
            'vacant_id' => ['required_without:entity_id', 'nullable', 'exists:vacants,id'],
            'entity_id' => ['required_without:vacant_id', 'nullable', 'exists:entities,id'],
        ];
    }

    public function messages()
    {
        return [
            'required_without' => 'Es requerido cuando :values no está presente.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'vacant_id' => 'Vacante',
            'entity_id' => 'Entidad internacional',
        ];
    }
}
