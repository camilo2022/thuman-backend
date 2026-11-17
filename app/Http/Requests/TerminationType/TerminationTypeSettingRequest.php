<?php

namespace App\Http\Requests\TerminationType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TerminationTypeSettingRequest extends FormRequest
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
            'settings.reason_required' => filter_var($this->input('settings.reason_required'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'settings.file_required' => filter_var($this->input('settings.file_required'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'settings.massive_allowed' => filter_var($this->input('settings.massive_allowed'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function rules()
    {
        return [
            'settings' => ['required', 'array'],
            'settings.reason_required' => ['required', 'boolean'],
            'settings.file_required' => ['required', 'boolean'],
            'settings.massive_allowed' => ['required', 'boolean'],
            'settings.message' => ['required', 'string', 'max:2000']
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
            'settings.reason_required' => 'Razónes',
            'settings.file_required' => 'Archivo',
            'settings.massive_allowed' => 'Masivo',
            'settings.message' => 'Mensaje'
        ];
    }
}
