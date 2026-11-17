<?php

namespace App\Http\Requests\Vacant\Application;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplicationAllRequest extends FormRequest
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
            'without_interview_initials' => filter_var($this->input('without_interview_initials'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'without_interview_psychotechnicals' => filter_var($this->input('without_interview_psychotechnicals'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'without_test_technicals' => filter_var($this->input('without_test_technicals'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'without_test_psychotechnicals' => filter_var($this->input('without_test_psychotechnicals'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'without_exam_medicals' => filter_var($this->input('without_exam_medicals'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'without_exam_polygraphs' => filter_var($this->input('without_exam_polygraphs'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }

    public function rules()
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'perPage' => ['nullable', 'numeric'],
            'person_id' => ['nullable', 'exists:people,id'],
            'without_interview_initials' => ['nullable', 'boolean'],
            'without_interview_psychotechnicals' => ['nullable', 'boolean'],
            'without_test_technicals' => ['nullable', 'boolean'],
            'without_test_psychotechnicals' => ['nullable', 'boolean'],
            'without_exam_medicals' => ['nullable', 'boolean'],
            'without_exam_polygraphs' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:' . implode(',', Application::statuses())]
        ];
    }

    public function messages()
    {
        return [
            'string' => 'Debe ser una cadena de caracteres.',
            'numeric' => 'Debe ser un valor numérico.',
            'exists' => 'No existe en la base de datos.',
            'boolean' => 'Deber ser requerido o no requerido.'
        ];
    }

    public function attributes()
    {
        return [
            'search' => 'Término de búsqueda',
            'perPage' => 'Número de registros por página',
            'person_id' => 'Persona',
            'without_interview_initials' => 'Sin aplicaciones con entrevista incial agendada',
            'without_interview_initials' => 'Sin aplicaciones con entrevista psicotécnica agendada',
            'without_interview_initials' => 'Sin aplicaciones con prueba técnica agendada',
            'without_test_psychotechnicals' => 'Sin aplicaciones con prueba psicotécnica agendada',
            'without_exam_medicals' => 'Sin aplicaciones con examen médico agendada',
            'without_exam_polygraphs' => 'Sin aplicaciones con examen polígrafo agendada',
            'status' => 'Estado'
        ];
    }
}
