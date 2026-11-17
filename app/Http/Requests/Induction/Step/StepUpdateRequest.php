<?php

namespace App\Http\Requests\Induction\Step;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StepUpdateRequest extends FormRequest
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
            'induction_id' => ['required', 'exists:inductions,id'],
            'name' => ['required', 'string', 'max:255', 'unique:steps,name,' . $this->route('stepId') . ',id,induction_id,' . $this->input('induction_id')],
            'description' => ['required', 'string', 'max:500'],
            'form_id' => ['required', 'exists:forms,id,form_type_id,139'],
            'video' => ['required', 'array'],
            'video.file' => ['nullable', 'file', 'mimes:mp4,mov,avi,wmv', 'max:20480'],
            'video.file_type_id' => ['required', 'exists:subitems,id,item_id,16', 'in:101'],
            'video.file_subtype_id' => ['required', 'exists:subitems,id,item_id,17', 'in:127'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'max' => 'No debe exceder los :max caracteres.',
            'exists' => 'No existe en la base de datos.',
            'unique' => 'Ya ha sido tomado.',
            'video.file.max' => 'No debe exceder los :max KB.',
            'array' => 'Debe ser una lista de datos.',
            'mimes' => 'Extensión inválida. Permitidas :values.',
            'file' => 'Debe ser un archivo.'
        ];
    }

    public function attributes()
    {
        return [
            'induction_id' => 'Inducción',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'form_id' => 'Formulario',
            'video' => 'Configuración video',
            'video.file' => 'Video',
            'video.file_type_id' => 'Tipo de archivo',
            'video.file_subtype_id' => 'Subtipo de archivo',
        ];
    }
}
