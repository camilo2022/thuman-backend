<?php

namespace App\Http\Requests\Vacant\Application\InterviewInitial;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InterviewInitialStoreRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id', 'unique:interview_initials,application_id'],
            'date_time' => ['required', 'date', 'after_or_equal:today'],
            'indication' => ['nullable', 'string', 'max:2000']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'unique' => 'Ya ha sido tomado.',
            'max' => 'No debe exceder los :max caracteres.',
            'string' => 'Debe ser una cadena de caracteres.',
            'date' => 'Debe ser una fecha.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.',
        ];
    }

    public function attributes()
    {
        return [
            'application_id' => 'Aplicación',
            'date_time' => 'Fecha y hora',
            'indication' => 'Indicación'
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

            if (!in_array($application->status, [Application::REVIEW], true)) {
                $validator->errors()->add('application_id', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::REVIEW]) . '.');
            }
        });
    }
}
