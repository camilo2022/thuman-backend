<?php

namespace App\Http\Requests\Vacant\Application\ExamPolygraph;

use App\Models\Application;
use App\Models\ExamPolygraph;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExamPolygraphUpdateRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id', 'unique:exam_polygraphs,application_id,' . $this->route('examPolygraphId') . ',id'],
            'date_time' => ['required', 'date', 'after_or_equal:today'],
            'city_id' => ['required', 'exists:cities,id'],
            'service_provider_id' => ['required', 'exists:subitems,id,item_id,35'],
            'address' => ['required', 'string', 'max:75'],
            'neighborhood' => ['required', 'string', 'max:50'],
            'indication' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:' . implode(',', ExamPolygraph::statuses())]
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
            'service_provider_id' => 'Proveedor de servicio',
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

            $examPolygraph = ExamPolygraph::find($this->route('examPolygraphId'));
            if (!$examPolygraph) {
                $validator->errors()->add('status', 'Examen médico no encontrada.');
                return;
            }

            if (in_array($examPolygraph->status, [ExamPolygraph::SCHEDULED], true) and in_array($this->input('status'), [ExamPolygraph::SCHEDULED])) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [ExamPolygraph::RESCHEDULED, ExamPolygraph::COMPLETED, ExamPolygraph::WITHDRAWN]) . '.');
            }

            if (in_array($examPolygraph->status, [ExamPolygraph::RESCHEDULED], true) and in_array($this->input('status'), [ExamPolygraph::SCHEDULED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [ExamPolygraph::RESCHEDULED, ExamPolygraph::COMPLETED, ExamPolygraph::WITHDRAWN]) . '.');
            }

            if (in_array($examPolygraph->status, [ExamPolygraph::WITHDRAWN, ExamPolygraph::COMPLETED], true)) {
                $validator->errors()->add('status', "No se permite cambiar el estado porque ya está en $examPolygraph->status.");
            }
        });
    }
}
