<?php

namespace App\Http\Requests\Vacant\Application\InterviewVideo;

use App\Models\Application;
use App\Models\InterviewVideo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InterviewVideoStoreRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id', 'unique:interview_videos,application_id'],
            'form_id' => ['required', 'exists:forms,id,form_type_id,140'],
            'questions' => ['required_with:form_id', 'array'],
            'answers' => ['required_with:form_id', 'array'],
            'reviewer_observation' => ['required', 'string', 'max:2000'],
            'reviewer_id' => ['required', 'exists:people,id'],
            'status' => ['required', 'in:' . implode(',', InterviewVideo::statuses())]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'required_with' => 'Es requerido cuando :values está presente.',
            'exists' => 'No existe en la base de datos.',
            'unique' => 'Ya ha sido tomado.',
            'array' => 'Debe ser una lista de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'application_id' => 'Aplicación',
            'form_id' => 'Formulario calificación video entrevista',
            'questions' => 'Preguntas',
            'answers' => 'Respuestas',
            'reviewer_observation' => 'Observacion Revisor',
            'reviewer_id' => 'Revisor',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $application = Application::find($this->integer('application_id'));
            if (!$application) {
                $validator->errors()->add('application_id', 'Aplicación no encontrada.');
                return;
            }

            if (!in_array($application->status, [Application::PENDING], true)) {
                $validator->errors()->add('application_id', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::PENDING]) . '.');
            }
        });
    }
}
