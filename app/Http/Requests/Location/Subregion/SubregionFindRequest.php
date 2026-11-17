<?php

namespace App\Http\Requests\Location\Subregion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubregionFindRequest extends FormRequest
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
            'name' => [$this->route('subregionId') ? 'nullable' : 'required', 'string', 'exists:subregions,name']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'exists' => 'No existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Subregion',
        ];
    }
}
