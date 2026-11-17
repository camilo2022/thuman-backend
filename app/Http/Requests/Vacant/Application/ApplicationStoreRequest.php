<?php

namespace App\Http\Requests\Vacant\Application;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Models\Vacant;

class ApplicationStoreRequest extends FormRequest
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
        $vacant = Vacant::find($this->input('vacant_id'));

        return [
            'person_id' => ['required', 'exists:people,id', 'unique:applications,person_id,NULL,id,vacant_id,' . $this->input('vacant_id')],
            'vacant_id' => ['required', 'exists:vacants,id'],
            'form_id' => ['nullable', 'exists:forms,id,form_type_id,134'],
            'questions' => ['required_with:form_id', 'array'],
            'answers' => ['required_with:form_id', 'array'],
            'video' => [$vacant?->settings?->video_interview ? 'required' : 'nullable', 'array'],
            'video.file' => [$vacant?->settings?->video_interview ? 'required' : 'nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska'],
            'video.file_type_id' => ['required_with:video.file', 'exists:subitems,id,item_id,16', 'in:100'],
            'video.file_subtype_id' => ['required_with:video.file', 'exists:subitems,id,item_id,17', 'in:127']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'unique' => 'Ya ha sido tomado.',
            'exists' => 'No existe en la base de datos.',
            'array' => 'Debe ser una lista de datos.',
            'mimetypes' => 'Extensión inválida. Permitidas :values.',
            'file' => 'Debe ser un archivo.',
        ];
    }

    public function attributes()
    {
        return [
            'person_id' => 'Persona',
            'vacant_id' => 'Vacante',
            'form_id' => 'Formulario',
            'questions' => 'Preguntas',
            'answers' => 'Respuestas',
            'video' => 'Configuración video entrevista',
            'video.file' => 'Video entrevista',
            'video.file_type_id' => 'Tipo de archivo',
            'video.file_subtype_id' => 'Subtipo de archivo',
        ];
    }

    protected function prepareForValidation()
    {
        if (is_string($this->questions)) {
            $this->merge(['questions' => json_decode($this->questions, true) ?? []]);
        }

        if (is_string($this->answers)) {
            $this->merge(['answers' => json_decode($this->answers, true) ?? []]);
        }
    }
}
