<?php

namespace App\Http\Requests\Vacant\Application\ExamMedical;

use App\Models\Application;
use App\Models\ExamMedical;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExamMedicalUpdateRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id', 'unique:exam_medicals,application_id,' . $this->route('examMedicalId') . ',id'],
            'date_time' => ['required', 'date', 'after_or_equal:today'],
            'city_id' => ['required', 'exists:cities,id'],
            'health_entity_id' => ['required', 'exists:subitems,id,item_id,26'],
            'address' => ['required', 'string', 'max:75'],
            'neighborhood' => ['required', 'string', 'max:50'],
            'indication' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:' . implode(',', ExamMedical::statuses())]
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
            'city_id' => 'Ciudad',
            'health_entity_id' => 'Entidad de salud',
            'address' => 'Dirección',
            'neighborhood' => 'Barrio',
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

            if (!in_array($application->status, [Application::FINALIST], true)) {
                $validator->errors()->add('application_id', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::FINALIST]) . '.');
            }

            $examMedical = ExamMedical::find($this->route('examMedicalId'));
            if (!$examMedical) {
                $validator->errors()->add('status', 'Examen médico no encontrada.');
                return;
            }

            if (in_array($examMedical->status, [ExamMedical::SCHEDULED], true) and in_array($this->input('status'), [ExamMedical::SCHEDULED])) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [ExamMedical::RESCHEDULED, ExamMedical::COMPLETED, ExamMedical::WITHDRAWN]) . '.');
            }

            if (in_array($examMedical->status, [ExamMedical::RESCHEDULED], true) and in_array($this->input('status'), [ExamMedical::SCHEDULED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [ExamMedical::RESCHEDULED, ExamMedical::COMPLETED, ExamMedical::WITHDRAWN]) . '.');
            }

            if (in_array($examMedical->status, [ExamMedical::WITHDRAWN], true)) {
                $validator->errors()->add('status', "No se permite cambiar el estado porque ya está en $examMedical->status.");
            }

            if (in_array($examMedical->status, [ExamMedical::COMPLETED], true) and in_array($this->input('status'), [ExamMedical::RESCHEDULED], true) and !in_array($examMedical->result, [ExamMedical::POSTPONED], true)) {
                $validator->errors()->add('status', "No se permite cambiar el estado porque ya está en $examMedical->status.");
            }
        });
    }
}
