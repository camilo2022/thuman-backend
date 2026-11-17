<?php

namespace App\Http\Requests\Vacant\Application\TestTechnical;

use App\Models\TestTechnical;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestTechnicalFinishRequest extends FormRequest
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
            'questions' => ['required', 'array'],
            'answers' => ['required', 'array'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'array' => 'Debe ser una lista de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'questions' => 'Preguntas',
            'answers' => 'Respuestas',
            'status' => 'Estado',
            'end_date' => 'Fecha cierre de prueba'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $testTechnical = TestTechnical::find($this->route('testTechnicalId'));
            if (!$testTechnical) {
                $validator->errors()->add('status', 'Prueba técnica no encontrada.');
                return;
            }

            if (!in_array($testTechnical->status, [TestTechnical::INPROGRESS], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [TestTechnical::INPROGRESS]) . '.');
            }

            if ($testTechnical->end_date && Carbon::now()->startOfDay()->gt($testTechnical->end_date)) {
                $validator->errors()->add('end_date', 'No se puede finalizar la prueba después de la fecha de fin ('.$testTechnical->end_date->format('Y-m-d').').');
            }
        });
    }
}
