<?php

namespace App\Http\Requests\Vacant\Application\TestTechnical;

use App\Models\TestTechnical;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestTechnicalStartRequest extends FormRequest
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
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [
            'status' => 'Estado',
            'token' => 'Llave de acceso',
            'start_date' => 'Fecha apertura prueba',
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

            if (!in_array($testTechnical->status, [TestTechnical::PENDING], true)) {
                $validator->errors()->add('status', 'Los estados permitidos para realizar esta acción son: '. implode(', ', [TestTechnical::PENDING]) . '.');
            }

            if ($testTechnical->start_date && Carbon::now()->startOfDay()->lt($testTechnical->start_date)) {
                $validator->errors()->add('start_date', 'No se puede iniciar la prueba antes de la fecha de inicio ('.$testTechnical->start_date->format('Y-m-d').').');
            }

            if ($testTechnical->end_date && Carbon::now()->startOfDay()->gt($testTechnical->end_date)) {
                $validator->errors()->add('end_date', 'No se puede iniciar la prueba después de la fecha de fin ('.$testTechnical->end_date->format('Y-m-d').').');
            }
        });
    }
}
