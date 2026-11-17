<?php

namespace App\Http\Requests\Vacant\Application\TestTechnical;

use App\Models\TestTechnical;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestTechnicalLeaderRequest extends FormRequest
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
            'leader_observation' => ['required', 'string', 'max:2000'],
            'leader_id' => ['required', 'exists:people,id'],
            'leader_status' => ['required', 'in:' . implode(',', [TestTechnical::APPROVED, TestTechnical::REJECTED])],
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
            'leader_observation' => 'Observacion Lider',
            'leader_id' => 'Lider',
            'leader_status' => 'Estado Lider',
            'status' => 'Estado'
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

            if (!in_array($testTechnical->status, [TestTechnical::FINISHED], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [TestTechnical::FINISHED]) . '.');
            }
        });
    }
}
