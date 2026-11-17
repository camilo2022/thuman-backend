<?php

namespace App\Http\Requests\Vacant\Application\TestPsychotechnical;

use App\Models\TestPsychotechnical;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestPsychotechnicalPsychologistRequest extends FormRequest
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
            'psychologist_observation' => ['required', 'string', 'max:2000'],
            'psychologist_id' => ['required', 'exists:people,id'],
            'psychologist_status' => ['required', 'in:' . implode(',', [TestPsychotechnical::APPROVED, TestPsychotechnical::REJECTED])],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Es requerido.',
            'string' => 'Debe ser una cadena de caracteres.',
            'integer' => 'Debe ser un valor numérico entero.',
            'exists' => 'No existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'psychologist_observation' => 'Observacion Piscólogo',
            'psychologist_id' => 'Piscólogo',
            'psychologist_status' => 'Estado Piscólogo',
            'status' => 'Estado'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $testPsychotechnical = TestPsychotechnical::find($this->route('testPsychotechnicalId'));
            if (!$testPsychotechnical) {
                $validator->errors()->add('status', 'Prueba psicotécnica no encontrada.');
                return;
            }

            if (!in_array($testPsychotechnical->status, [TestPsychotechnical::FINISHED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [TestPsychotechnical::FINISHED]) . '.');
            }
        });
    }
}
