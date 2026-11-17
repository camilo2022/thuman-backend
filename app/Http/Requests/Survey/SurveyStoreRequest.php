<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SurveyStoreRequest extends FormRequest
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
            'form_id' => ['required', 'exists:forms,id', 'unique:surveys,form_id,NULL,id,person_id,'.$this->input('person_id')],
            'questions' => ['required', 'array'],
            'answers' => ['required', 'array'],
            'person_id' => ['required', 'exists:people,id']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'unique' => 'Ya has contestado la encuesta',
            'array' => 'Debe ser una lista de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'form_id' => 'Formulario',
            'questions' => 'Preguntas',
            'answers' => 'Respuestas',
            'person_id' => 'Persona'
        ];
    }
}
