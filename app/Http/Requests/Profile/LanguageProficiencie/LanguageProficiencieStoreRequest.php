<?php

namespace App\Http\Requests\Profile\LanguageProficiencie;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LanguageProficiencieStoreRequest extends FormRequest
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
            'language_id' => ['required', 'exists:subitems,id,item_id,21', 'unique:language_proficiencies,language_id,NULL,id,profile_id,' . $this->input('profile_id')],
            'language_level_id' => ['required', 'exists:subitems,id,item_id,20'],
            'file' => ['required', 'array'],
            'file.file' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'file.file_type_id' => ['required_with:file.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'file.file_subtype_id' => ['required_with:file.file', 'exists:subitems,id,item_id,17', 'in:122']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'unique' => 'Ya ha sido tomado.',
            'exists' => 'No existe en la base de datos.',
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
            'language_id' => 'Idioma',
            'language_level_id' => 'Nivel idiomatico',
            'file' => 'Configuración certificado',
            'file.file' => 'Certificado',
            'file.file_type_id' => 'Tipo de archivo',
            'file.file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
