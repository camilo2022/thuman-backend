<?php

namespace App\Http\Requests\License;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LicenseUpdateRequest extends FormRequest
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
            'person_id' => ['required', 'exists:people,id', 'exists:employees,person_id,deleted_at,NULL'],
            'license_type_id' => ['required', 'exists:subitems,id,item_id,44'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:10', 'max:2000'],
            'file' => ['sometimes', 'array'],
            'file.file' => ['sometimes', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:127'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'min' => 'Debe ser mínimo :min.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.',
            'file' => 'Debe ser un archivo.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'file.file.max' => 'No debe exceder los :max KB.'
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'license_type_id' => 'Tipo de licencia',
            'start_date' => 'Fecha inicio',
            'end_date' => 'Fecha fin',
            'reason' => 'Motivo',
            'file' => 'Configuración anexo',
            'file.file' => 'Anexo',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
