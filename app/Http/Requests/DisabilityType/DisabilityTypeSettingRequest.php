<?php

namespace App\Http\Requests\DisabilityType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DisabilityTypeSettingRequest extends FormRequest
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
            'settings.file_required' => filter_var($this->input('settings.file_required'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function rules()
    {
        return [
            'settings' => ['required', 'array'],
            'settings.file_required' => ['required', 'boolean'],
            'settings.message' => ['required', 'string', 'max:5000']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'unique' => 'Ya ha sido tomado.',
            'array' => 'Debe ser una lista de datos.',
            'max' => 'No debe exceder los :max caracteres.',
            'boolean' => 'Deber ser requerido o no requerido.'
        ];
    }

    public function attributes()
    {
        return [
            'settings' => 'Configuración',
            'settings.file_required' => 'Archivo',
            'settings.message' => 'Mensaje'
        ];
    }
}
