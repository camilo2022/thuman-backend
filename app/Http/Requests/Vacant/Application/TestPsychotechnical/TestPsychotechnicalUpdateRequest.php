<?php

namespace App\Http\Requests\Vacant\Application\TestPsychotechnical;

use App\Models\Application;
use App\Models\TestPsychotechnical;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestPsychotechnicalUpdateRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id', 'unique:test_psychotechnicals,application_id,' . $this->route('testPsychotechnicalId') . ',id'],
            'form_id' => ['required', 'exists:forms,id,form_type_id,136'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'indication' => ['nullable', 'string', 'max:2000']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'exists' => 'No existe en la base de datos.',
            'date' => 'Debe ser una fecha válida.',
            'date_format' => 'Formato inválido. Permitido :format.',
            'after_or_equal' => 'Debe ser una fecha posterior o igual a hoy.',
            'max' => 'No debe exceder los :max caracteres.',
            'string' => 'Debe ser una cadena de caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'application_id' => 'Aplicación',
            'form_id' => 'Prueba psicotécnica',
            'start_date' => 'Fecha inicio',
            'end_date' => 'Fecha fin',
            'status' => 'Estado',
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

            if (!in_array($application->status, [Application::TEST], true)) {
                $validator->errors()->add('application_id', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [Application::TEST]) . '.');
            }

            $testPsychotechnical = TestPsychotechnical::find($this->route('testPsychotechnicalId'));
            if (!$testPsychotechnical) {
                $validator->errors()->add('status', 'Prueba psicotécnica no encontrada.');
                return;
            }

            if (!in_array($testPsychotechnical->status, [TestPsychotechnical::PENDING], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [TestPsychotechnical::PENDING]) . '.');
            }
        });
    }
}
