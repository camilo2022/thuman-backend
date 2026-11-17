<?php

namespace App\Http\Requests\Vacant\Application\InterviewPsychotechnical;

use App\Models\Application;
use App\Models\InterviewPsychotechnical;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InterviewPsychotechnicalUpdateRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id', 'unique:interview_psychotechnicals,application_id,' . $this->route('interviewPsychotechnicalId') . ',id'],
            'date_time' => ['required', 'date', 'after_or_equal:today'],
            'indication' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:' . implode(',', InterviewPsychotechnical::statuses())]
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
            'indication' => 'Indicación',
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

            if (!in_array($application->status, [Application::REVIEW], true)) {
                $validator->errors()->add('application_id', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::REVIEW]) . '.');
            }

            $interviewPsychotechnical = InterviewPsychotechnical::find($this->route('interviewPsychotechnicalId'));
            if (!$interviewPsychotechnical) {
                $validator->errors()->add('status', 'Entrevista psicotécnica no encontrada.');
                return;
            }

            if (in_array($interviewPsychotechnical->status, [InterviewPsychotechnical::SCHEDULED], true) and in_array($this->input('status'), [InterviewPsychotechnical::SCHEDULED])) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [InterviewPsychotechnical::RESCHEDULED, InterviewPsychotechnical::COMPLETED]) . '.');
            }

            if (in_array($interviewPsychotechnical->status, [InterviewPsychotechnical::RESCHEDULED], true) and in_array($this->input('status'), [InterviewPsychotechnical::SCHEDULED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [InterviewPsychotechnical::RESCHEDULED, InterviewPsychotechnical::COMPLETED]) . '.');
            }

            if (in_array($interviewPsychotechnical->status, [InterviewPsychotechnical::WITHDRAWN, InterviewPsychotechnical::COMPLETED], true)) {
                $validator->errors()->add('status', "No se permite cambiar el estado porque ya está en $interviewPsychotechnical->status.");
            }
        });
    }
}
