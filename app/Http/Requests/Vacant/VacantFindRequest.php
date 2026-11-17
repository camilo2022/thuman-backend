<?php

namespace App\Http\Requests\Vacant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VacantFindRequest extends FormRequest
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
            'vacant_uuid' => [$this->route('vacantId') ? 'nullable' : 'required', 'uuid', 'exists:vacants,uuid']
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
            'vacant_uuid' => 'Vacante'
        ];
    }
}
