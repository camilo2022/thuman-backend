<?php

namespace App\Http\Requests\Profile\AcademicQualification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AcademicQualificationStoreRequest extends FormRequest
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
            'profile_id' => ['required', 'exists:profiles,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'academic_level_id' => ['required', 'exists:subitems,id,item_id,8'],
            'academic_institution' => ['required', 'string', 'max:255'],
            'academic_title' => ['required', 'string', 'max:255'],
            'formation_status_id' => ['required', 'exists:subitems,id,item_id,7'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'file' => ['required', 'array'],
            'file.file' => [$this->input('formation_status_id') == 27 ? 'required' : 'sometimes', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:122']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'exists' => 'No existe en la base de datos.',
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'max' => 'No debe exceder los :max caracteres.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.',
            'file.file.max' => 'No debe exceder los :max KB.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'file' => 'Debe ser un archivo.',
        ];
    }

    public function attributes()
    {
        return [
            'profile_id' => 'Perfil',
            'city_id' => 'Ciudad',
            'academic_level_id' => 'Nivel academico',
            'academic_institution' => 'Institución academica',
            'academic_title' => 'Titulo academico',
            'formation_status_id' => 'Estado de la formacion',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de finalización',
            'file' => 'Configuración certificado',
            'file.file' => 'Certificado',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
