<?php

namespace App\Http\Requests\Vacant\Application;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplicationFindRequest extends FormRequest
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
            'person_id' => [$this->route('applicationId') ? 'nullable' : 'required', 'exists:people,id'],
            'vacant_id' => [$this->route('applicationId') ? 'nullable' : 'required', 'exists:vacants,id']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'vacant_id' => 'Vacante'
        ];
    }
}
